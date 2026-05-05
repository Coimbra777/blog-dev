<?php

namespace App\Services\Blog;

use App\Support\LocalizedRoute;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

final readonly class BlogPostData
{
    /**
     * @param  list<string>  $tags
     */
    public function __construct(
        public string $title,
        public string $slug,
        public string $description,
        public string $locale,
        public string $translationKey,
        public CarbonImmutable $date,
        public array $tags,
        public bool $draft,
        public string $markdown,
        public ?string $html,
        public int $readingTimeMinutes,
    ) {
    }

    /**
     * @param  list<string>  $tags
     */
    public function withHtml(string $html): self
    {
        return new self(
            title: $this->title,
            slug: $this->slug,
            description: $this->description,
            locale: $this->locale,
            translationKey: $this->translationKey,
            date: $this->date,
            tags: $this->tags,
            draft: $this->draft,
            markdown: $this->markdown,
            html: $html,
            readingTimeMinutes: $this->readingTimeMinutes,
        );
    }

    public function formattedDate(string $locale): string
    {
        $normalizedLocale = LocalizedRoute::normalize($locale);
        $date = $this->date->locale(LocalizedRoute::dateLocale($normalizedLocale));

        if ($normalizedLocale === LocalizedRoute::ENGLISH_LOCALE) {
            return $date->translatedFormat('F j, Y');
        }

        return sprintf(
            '%d de %s, %d',
            $date->day,
            Str::ucfirst($date->translatedFormat('F')),
            $date->year,
        );
    }
}
