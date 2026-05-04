<?php

namespace App\Http\Controllers\Concerns;

use App\Support\LocalizedRoute;

trait InteractsWithLocalizedViews
{
    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    protected function localizedViewData(string $locale, array $extra = []): array
    {
        $locale = LocalizedRoute::normalize($locale);

        return array_merge([
            'currentLocale' => $locale,
            'homeUrl' => route(LocalizedRoute::routeName($locale, 'home')),
            'blogIndexUrl' => route(LocalizedRoute::routeName($locale, 'blog.index')),
            'aboutUrl' => route(LocalizedRoute::routeName($locale, 'home')).'#sobre',
        ], $extra);
    }
}
