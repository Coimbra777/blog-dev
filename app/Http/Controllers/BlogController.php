<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithLocalizedViews;
use App\Services\Blog\BlogPostService;
use App\Support\LocalizedRoute;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    use InteractsWithLocalizedViews;

    public function __construct(
        private readonly BlogPostService $blogPosts,
    ) {
    }

    public function index(Request $request): View
    {
        $locale = $this->setLocale((string) $request->route('locale', 'pt'));

        return view('blog.index', $this->localizedViewData($locale, [
            'posts' => $this->blogPosts->all($locale),
            'availableTags' => $this->blogPosts->availableTags($locale),
            'canonical' => route(LocalizedRoute::routeName($locale, 'blog.index')),
            'localeUrls' => $this->localeUrlsForIndex(),
        ]));
    }

    public function show(Request $request, string $slug): View
    {
        $locale = $this->setLocale((string) $request->route('locale', 'pt'));
        $post = $this->blogPosts->findBySlug($locale, $slug);

        abort_unless($post !== null, 404);

        $post = $this->blogPosts->withRenderedHtml($post);

        return view('blog.show', $this->localizedViewData($locale, [
            'post' => $post,
            'canonical' => route(LocalizedRoute::routeName($locale, 'blog.show'), $post->slug),
            'localeUrls' => $this->localeUrlsForPost($post),
        ]));
    }

    public function tag(Request $request, string $tag): View
    {
        $locale = $this->setLocale((string) $request->route('locale', 'pt'));
        $normalizedTag = Str::of($tag)->trim()->lower()->value();

        return view('blog.tag', $this->localizedViewData($locale, [
            'tag' => $normalizedTag,
            'posts' => $this->blogPosts->byTag($locale, $normalizedTag),
            'availableTags' => $this->blogPosts->availableTags($locale),
            'canonical' => route(LocalizedRoute::routeName($locale, 'blog.tag'), $normalizedTag),
            'localeUrls' => $this->localeUrlsForTag($normalizedTag),
        ]));
    }

    /**
     * @return array<string, string>
     */
    private function localeUrlsForIndex(): array
    {
        return [
            'pt' => route('blog.index'),
            // 'en' => route('en.blog.index'), // PT/EN: rotas /en desativadas
        ];
    }

    /**
     * @return array<string, string>
     */
    private function localeUrlsForTag(string $tag): array
    {
        return [
            'pt' => route('blog.tag', $tag),
            // 'en' => route('en.blog.tag', $tag), // PT/EN: rotas /en desativadas
        ];
    }

    /**
     * @return array<string, string>
     */
    private function localeUrlsForPost(\App\Services\Blog\BlogPostData $post): array
    {
        // $englishTranslation = $this->blogPosts->findTranslation($post, 'en'); // PT/EN
        $portugueseTranslation = $this->blogPosts->findTranslation($post, 'pt');

        return [
            'pt' => $post->locale === 'pt'
                ? route('blog.show', $post->slug)
                : ($portugueseTranslation !== null ? route('blog.show', $portugueseTranslation->slug) : route('blog.index')),
            /*
            'en' => $post->locale === 'en'
                ? route('en.blog.show', $post->slug)
                : ($englishTranslation !== null ? route('en.blog.show', $englishTranslation->slug) : route('en.blog.index')),
            */
        ];
    }

    private function setLocale(string $locale): string
    {
        $locale = LocalizedRoute::normalize($locale);

        App::setLocale($locale);

        return $locale;
    }
}
