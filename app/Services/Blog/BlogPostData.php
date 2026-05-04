<?php

namespace App\Services\Blog;

use Carbon\CarbonImmutable;

final readonly class BlogPostData
{
    /**
     * @param  list<string>  $tags
     */
    public function __construct(
        public string $title,
        public string $slug,
        public string $description,
        public CarbonImmutable $date,
        public array $tags,
        public bool $draft,
        public string $markdown,
        public string $html,
        public int $readingTimeMinutes,
    ) {
    }

    public function formattedDate(): string
    {
        return $this->date->format('d/m/Y');
    }
}
