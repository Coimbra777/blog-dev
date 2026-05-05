<?php

namespace App\Services\Blog;

use App\Support\LocalizedRoute;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use InvalidArgumentException;
use League\CommonMark\GithubFlavoredMarkdownConverter;
use Symfony\Component\Yaml\Yaml;

class BlogPostService
{
    private const CACHE_TTL_MINUTES = 5;

    /**
     * Versão da estrutura em cache (posts keyed por slug + HTML lazy).
     */
    private const POSTS_CACHE_VERSION = 'v2';

    private ?GithubFlavoredMarkdownConverter $markdownConverter = null;

    /**
     * @return Collection<int, BlogPostData>
     */
    public function all(string $locale, ?bool $includeDrafts = null): Collection
    {
        return $this->orderedPosts($locale)
            ->filter(fn (BlogPostData $post) => $this->shouldExpose($post, $includeDrafts))
            ->values();
    }

    public function findBySlug(string $locale, string $slug, ?bool $includeDrafts = null): ?BlogPostData
    {
        $normalizedSlug = $this->normalizeIncomingSlug($slug);
        $post = $this->postsKeyedBySlug($locale)->get($normalizedSlug);

        if ($post === null || ! $this->shouldExpose($post, $includeDrafts)) {
            return null;
        }

        return $post;
    }

    /**
     * Converte Markdown para HTML apenas quando necessário (ex.: página do post), com cache por slug + conteúdo.
     */
    public function withRenderedHtml(BlogPostData $post): BlogPostData
    {
        if ($post->html !== null) {
            return $post;
        }

        $htmlCacheKey = $this->htmlCacheKey($post);

        $html = Cache::remember(
            $htmlCacheKey,
            now()->addMinutes(self::CACHE_TTL_MINUTES),
            fn () => (string) $this->markdownConverter()->convert($post->markdown),
        );

        return $post->withHtml($html);
    }

    /**
     * @return Collection<int, BlogPostData>
     */
    public function byTag(string $locale, string $tag, ?bool $includeDrafts = null): Collection
    {
        $normalizedTag = Str::of($tag)->trim()->lower()->value();

        return $this->all($locale, $includeDrafts)
            ->filter(fn (BlogPostData $post) => in_array($normalizedTag, $post->tags, true))
            ->values();
    }

    /**
     * @return Collection<int, string>
     */
    public function availableTags(string $locale, ?bool $includeDrafts = null): Collection
    {
        return $this->all($locale, $includeDrafts)
            ->flatMap(fn (BlogPostData $post) => $post->tags)
            ->unique()
            ->sort()
            ->values();
    }

    public function findTranslation(BlogPostData $post, string $targetLocale, ?bool $includeDrafts = null): ?BlogPostData
    {
        return $this->all($targetLocale, $includeDrafts)
            ->first(fn (BlogPostData $candidate) => $candidate->translationKey === $post->translationKey);
    }

    /**
     * Posts ordenados por data (mais recentes primeiro), incluindo rascunhos conforme includeDrafts implícito no uso via {@see all()}.
     *
     * @return Collection<int, BlogPostData>
     */
    private function orderedPosts(string $locale): Collection
    {
        return $this->postsKeyedBySlug($locale)
            ->values()
            ->sortByDesc(fn (BlogPostData $post) => $post->date->timestamp)
            ->values();
    }

    /**
     * @return Collection<string, BlogPostData>
     */
    private function postsKeyedBySlug(string $locale): Collection
    {
        $normalizedLocale = $this->normalizeLocale($locale);
        $files = $this->postFiles($normalizedLocale);
        $fingerprint = md5(collect($files)
            ->map(fn (string $path) => $path.':'.filemtime($path))
            ->implode('|'));

        /** @var Collection<string, BlogPostData> $keyed */
        $keyed = Cache::remember(
            'blog.posts.'.self::POSTS_CACHE_VERSION.'.'.$normalizedLocale.'.'.$fingerprint,
            now()->addMinutes(self::CACHE_TTL_MINUTES),
            fn () => $this->parsePostsKeyedBySlug($files, $normalizedLocale),
        );

        return $keyed;
    }

    /**
     * @param  list<string>  $files
     * @return Collection<string, BlogPostData>
     */
    private function parsePostsKeyedBySlug(array $files, string $locale): Collection
    {
        return collect($files)
            ->map(function (string $path) use ($locale): ?BlogPostData {
                try {
                    return $this->parsePostWithoutHtml($path, $locale);
                } catch (\Throwable $e) {
                    Log::warning('Blog: arquivo de post ignorado após erro de parse.', [
                        'path' => $path,
                        'exception' => $e::class,
                        'message' => $e->getMessage(),
                    ]);

                    return null;
                }
            })
            ->filter()
            /** @var Collection<int, BlogPostData> $parsed */
            ->keyBy(fn (BlogPostData $post) => $post->slug);
    }

    /**
     * @return list<string>
     */
    private function postFiles(string $locale): array
    {
        $path = resource_path('posts/'.$locale);

        if (! File::isDirectory($path)) {
            return [];
        }

        $files = collect(File::files($path))
            ->filter(fn (\SplFileInfo $file) => $file->getExtension() === 'md')
            ->sortBy(fn (\SplFileInfo $file) => $file->getFilename())
            ->map(fn (\SplFileInfo $file) => $file->getPathname())
            ->values()
            ->all();

        return $files;
    }

    private function parsePostWithoutHtml(string $path, string $locale): BlogPostData
    {
        $contents = File::get($path);
        [$frontMatter, $markdown] = $this->extractFrontMatter($contents, $path);

        try {
            $metadata = Yaml::parse($frontMatter);
        } catch (\Throwable $e) {
            throw new InvalidArgumentException("YAML do front matter inválido no post [{$path}]: ".$e->getMessage(), 0, $e);
        }

        if (! is_array($metadata)) {
            throw new InvalidArgumentException("Front matter inválido no post [{$path}].");
        }

        $title = $this->stringMetadata($metadata, 'title', $path);
        $slug = $this->normalizeSlug($this->stringMetadata($metadata, 'slug', $path), $path);
        $description = $this->stringMetadata($metadata, 'description', $path);
        $translationKey = $this->stringMetadata($metadata, 'translation_key', $path);
        $date = $this->parseDate($this->stringMetadata($metadata, 'date', $path), $path);
        $tags = $this->parseTags($metadata['tags'] ?? [], $path);
        $draft = (bool) ($metadata['draft'] ?? false);

        return new BlogPostData(
            title: $title,
            slug: $slug,
            description: $description,
            locale: $locale,
            translationKey: $translationKey,
            date: $date,
            tags: $tags,
            draft: $draft,
            markdown: $markdown,
            html: null,
            readingTimeMinutes: $this->estimateReadingTime($markdown),
        );
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function extractFrontMatter(string $contents, string $path): array
    {
        $matches = [];
        $hasFrontMatter = preg_match('/\A---\R(.*?)\R---\R?(.*)\z/s', $contents, $matches) === 1;

        if (! $hasFrontMatter) {
            throw new InvalidArgumentException("Post sem front matter YAML válido: [{$path}].");
        }

        return [$matches[1], trim($matches[2])];
    }

    /**
     * @param  array<string, mixed>  $metadata
     */
    private function stringMetadata(array $metadata, string $key, string $path): string
    {
        $value = $metadata[$key] ?? null;

        if (! is_string($value) || trim($value) === '') {
            throw new InvalidArgumentException("Campo [{$key}] inválido no post [{$path}].");
        }

        return trim($value);
    }

    private function normalizeSlug(string $slug, string $path): string
    {
        $normalized = Str::of($slug)->trim()->lower()->slug('-')->value();

        if ($normalized === '') {
            throw new InvalidArgumentException("Slug inválido no post [{$path}].");
        }

        return $normalized;
    }

    private function normalizeIncomingSlug(string $slug): string
    {
        return Str::of($slug)->trim()->lower()->slug('-')->value();
    }

    private function parseDate(string $date, string $path): CarbonImmutable
    {
        $parsedDate = CarbonImmutable::createFromFormat('Y-m-d', $date);

        if ($parsedDate === false) {
            throw new InvalidArgumentException("Data inválida no post [{$path}].");
        }

        return $parsedDate->startOfDay();
    }

    /**
     * @param  mixed  $tags
     * @return list<string>
     */
    private function parseTags(mixed $tags, string $path): array
    {
        if (! is_array($tags)) {
            throw new InvalidArgumentException("Campo [tags] inválido no post [{$path}].");
        }

        $normalizedTags = collect($tags)
            ->filter(fn (mixed $tag) => is_string($tag) && trim($tag) !== '')
            ->map(fn (string $tag) => Str::of($tag)->trim()->lower()->value())
            ->unique()
            ->values()
            ->all();

        if ($normalizedTags === []) {
            throw new InvalidArgumentException("O post [{$path}] precisa de pelo menos uma tag.");
        }

        return $normalizedTags;
    }

    private function estimateReadingTime(string $markdown): int
    {
        preg_match_all('/[\p{L}\p{N}_-]+/u', strip_tags($markdown), $matches);

        return max(1, (int) ceil(count($matches[0]) / 200));
    }

    private function htmlCacheKey(BlogPostData $post): string
    {
        return 'blog.post.html.'.self::POSTS_CACHE_VERSION.'.'.md5($post->locale.'|'.$post->slug.'|'.$post->markdown);
    }

    private function shouldExpose(BlogPostData $post, ?bool $includeDrafts = null): bool
    {
        return ! $post->draft || $this->shouldIncludeDrafts($includeDrafts);
    }

    private function shouldIncludeDrafts(?bool $includeDrafts = null): bool
    {
        return $includeDrafts ?? config('app.env') === 'local';
    }

    private function normalizeLocale(string $locale): string
    {
        return LocalizedRoute::normalize($locale);
    }

    private function markdownConverter(): GithubFlavoredMarkdownConverter
    {
        if ($this->markdownConverter !== null) {
            return $this->markdownConverter;
        }

        return $this->markdownConverter = new GithubFlavoredMarkdownConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }
}
