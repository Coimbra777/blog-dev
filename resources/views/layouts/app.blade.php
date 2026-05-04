@php
    use App\Support\LocalizedRoute;

    $currentLocale = $currentLocale ?? LocalizedRoute::normalize(app()->getLocale());
    $homeUrl = $homeUrl ?? route(LocalizedRoute::routeName($currentLocale, 'home'));
    $blogIndexUrl = $blogIndexUrl ?? route(LocalizedRoute::routeName($currentLocale, 'blog.index'));
    $aboutUrl = $aboutUrl ?? ($homeUrl.'#sobre');
    $localeUrls = $localeUrls ?? [
        'pt' => route('home'),
        'en' => route('en.home'),
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $currentLocale) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $metaTitle ?? __('blog.site_title') }}</title>
        <meta name="description" content="{{ $metaDescription ?? __('blog.site_description') }}">
        @isset($canonical)
            <link rel="canonical" href="{{ $canonical }}">
        @endisset

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=ibm-plex-sans:400,500,600,700|ibm-plex-mono:400,500" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>

    <body class="min-h-screen bg-[#151515] text-[#f9f9f9] antialiased">
        <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(252,142,0,0.08),_transparent_28%),linear-gradient(180deg,_rgba(255,255,255,0.02),_transparent_22%)]">
            <header class="border-b border-white/10">
                <div class="mx-auto w-full max-w-5xl px-6 py-6 lg:px-8">
                    <div class="flex items-center justify-between text-[0.7rem] font-medium uppercase tracking-[0.34em] text-white/45">
                        <span>{{ __('blog.brand') }}</span>
                        <div class="flex items-center gap-2">
                            <a href="{{ $localeUrls['pt'] }}" class="{{ $currentLocale === 'pt' ? 'text-[#fc8e00]' : 'text-white/45 hover:text-white/70' }} transition focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                                {{ __('blog.language.pt') }}
                            </a>
                            <span>/</span>
                            <a href="{{ $localeUrls['en'] }}" class="{{ $currentLocale === 'en' ? 'text-[#fc8e00]' : 'text-white/45 hover:text-white/70' }} transition focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                                {{ __('blog.language.en') }}
                            </a>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                        <a href="{{ $homeUrl }}" class="text-2xl font-semibold tracking-tight text-[#f9f9f9] transition hover:text-[#fc8e00]">
                            {{ __('blog.brand_short') }}
                        </a>

                        <nav class="flex items-center gap-5 text-sm text-white/70">
                            <a href="{{ $homeUrl }}" class="transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">{{ __('blog.nav.home') }}</a>
                            <a href="{{ $blogIndexUrl }}" class="transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">{{ __('blog.nav.posts') }}</a>
                            <a href="{{ $aboutUrl }}" class="transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">{{ __('blog.nav.about') }}</a>
                        </nav>
                    </div>
                </div>
            </header>

            <main class="mx-auto w-full max-w-5xl px-6 py-12 lg:px-8 lg:py-16">
                @yield('content')
            </main>

            <footer class="border-t border-white/10">
                <div class="mx-auto w-full max-w-5xl px-6 py-6 text-sm text-white/45 lg:px-8">
                    © 2026 {{ __('blog.brand') }}. {{ __('blog.footer') }}
                </div>
            </footer>
        </div>
    </body>
</html>
