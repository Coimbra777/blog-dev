<?php

namespace App\Support;

final class LocalizedRoute
{
    public const DEFAULT_LOCALE = 'pt';

    public const ENGLISH_LOCALE = 'en';

    public static function normalize(?string $locale): string
    {
        return $locale === self::ENGLISH_LOCALE
            ? self::ENGLISH_LOCALE
            : self::DEFAULT_LOCALE;
    }

    public static function other(string $locale): string
    {
        return self::normalize($locale) === self::ENGLISH_LOCALE
            ? self::DEFAULT_LOCALE
            : self::ENGLISH_LOCALE;
    }

    public static function routeName(string $locale, string $route): string
    {
        return self::normalize($locale) === self::ENGLISH_LOCALE
            ? 'en.'.$route
            : $route;
    }

    public static function dateLocale(string $locale): string
    {
        return self::normalize($locale) === self::ENGLISH_LOCALE ? 'en' : 'pt_BR';
    }
}
