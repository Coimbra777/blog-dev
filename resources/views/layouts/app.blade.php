@php
    use App\Support\LocalizedRoute;

    $currentLocale = $currentLocale ?? LocalizedRoute::normalize(app()->getLocale());
    $homeUrl = $homeUrl ?? route(LocalizedRoute::routeName($currentLocale, 'home'));
    $blogIndexUrl = $blogIndexUrl ?? route(LocalizedRoute::routeName($currentLocale, 'blog.index'));
    $aboutUrl = $aboutUrl ?? $homeUrl . '#sobre';
    $contactUrl = $contactUrl ?? $homeUrl . '#contato';
    $localeUrls = $localeUrls ?? [
        'pt' => route('home'),
        // 'en' => route('en.home'), // PT/EN: rotas /en desativadas
    ];
    $portfolioName = config('portfolio.name');
    $contactLinks = array_values(array_filter([
        [
            'label' => __('blog.contact.email'),
            'href' => config('portfolio.email') ? 'mailto:' . config('portfolio.email') : null,
            'value' => config('portfolio.email'),
        ],
        [
            'label' => __('blog.contact.linkedin'),
            'href' => config('portfolio.linkedin'),
            'value' => config('portfolio.linkedin'),
        ],
        [
            'label' => __('blog.contact.github'),
            'href' => config('portfolio.github'),
            'value' => config('portfolio.github'),
        ],
        [
            'label' => __('blog.contact.whatsapp'),
            'href' => config('portfolio.whatsapp'),
            'value' => config('portfolio.whatsapp'),
        ],
    ], static fn (array $link): bool => filled($link['href'])));
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

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=ibm-plex-sans:400,500,600,700|ibm-plex-mono:400,500"
        rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>

<body class="min-h-screen bg-[#151515] text-[#f9f9f9] antialiased">
    <div
        class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(252,142,0,0.08),_transparent_28%),linear-gradient(180deg,_rgba(255,255,255,0.02),_transparent_22%)]">
        <header class="border-b border-white/10">
            <div class="mx-auto w-full max-w-5xl px-6 py-3 lg:px-8">
                <div
                    class="flex items-center justify-between text-[0.7rem] font-medium uppercase tracking-[0.34em] text-white/45">
                    <span>{{ __('blog.brand') }}</span>
                    {{-- PT/EN: seletor de idioma desativado
                        <div class="flex items-center gap-2">
                            <a href="{{ $localeUrls['pt'] }}" class="{{ $currentLocale === 'pt' ? 'text-[#fc8e00]' : 'text-white/45 hover:text-white/70' }} transition focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                                {{ __('blog.language.pt') }}
                            </a>
                            <span>/</span>
                            <a href="{{ $localeUrls['en'] }}" class="{{ $currentLocale === 'en' ? 'text-[#fc8e00]' : 'text-white/45 hover:text-white/70' }} transition focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                                {{ __('blog.language.en') }}
                            </a>
                        </div>
                        --}}
                </div>

                <div class="mt-2 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ $homeUrl }}"
                        class="text-2xl font-semibold tracking-tight text-[#f9f9f9] transition hover:text-[#fc8e00]">
                        {{ __('blog.brand_short') }}
                    </a>

                    <nav class="flex flex-wrap items-center gap-5 text-sm text-white/70">
                        <a href="{{ $blogIndexUrl }}"
                            class="transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">{{ __('blog.nav.posts') }}</a>
                        <a href="{{ $aboutUrl }}"
                            class="transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">{{ __('blog.nav.about') }}</a>
                        <a href="{{ $contactUrl }}"
                            class="transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">{{ __('blog.nav.contact') }}</a>
                    </nav>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-5xl px-6 py-12 lg:px-8 lg:py-16">
            @yield('content')
        </main>

        <footer class="border-t border-white/10">
            <div class="mx-auto grid w-full max-w-5xl gap-10 px-6 py-10 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)_minmax(0,1fr)] lg:px-8">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.28em] text-[#fc8e00]">{{ $portfolioName }}</p>
                    <p class="max-w-md text-sm leading-7 text-white/55">{{ __('blog.footer.tagline') }}</p>
                    <p class="text-sm text-white/38">© {{ now()->year }} {{ $portfolioName }}</p>
                </div>

                <div class="space-y-4">
                    <p class="text-xs font-medium uppercase tracking-[0.28em] text-white/45">{{ __('blog.footer.quick_links') }}</p>
                    <ul class="space-y-3 text-sm text-white/65">
                        <li><a href="{{ $blogIndexUrl }}" class="transition hover:text-[#fc8e00]">{{ __('blog.nav.posts') }}</a></li>
                        <li><a href="{{ $aboutUrl }}" class="transition hover:text-[#fc8e00]">{{ __('blog.nav.about') }}</a></li>
                        <li><a href="{{ $contactUrl }}" class="transition hover:text-[#fc8e00]">{{ __('blog.nav.contact') }}</a></li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <p class="text-xs font-medium uppercase tracking-[0.28em] text-white/45">{{ __('blog.footer.social_links') }}</p>
                    <ul class="space-y-3 text-sm text-white/65">
                        @forelse ($contactLinks as $contactLink)
                            <li>
                                <a href="{{ $contactLink['href'] }}" class="transition hover:text-[#fc8e00]">
                                    {{ $contactLink['label'] }}
                                </a>
                            </li>
                        @empty
                            <li class="text-white/38">{{ __('blog.contact.empty') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>
