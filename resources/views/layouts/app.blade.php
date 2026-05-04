<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $metaTitle ?? config('app.name', 'Laravel').' | Blog' }}</title>
        <meta name="description" content="{{ $metaDescription ?? 'Artigos técnicos sobre backend, Laravel, Docker e arquitetura simples.' }}">
        @isset($canonical)
            <link rel="canonical" href="{{ $canonical }}">
        @endisset

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=ibm-plex-sans:400,500,600,700|ibm-plex-mono:400,500" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(14,116,144,0.12),_transparent_35%),linear-gradient(180deg,_#f8fafc_0%,_#eef2ff_100%)] text-slate-900">
        <div class="min-h-screen">
            <header class="border-b border-slate-200/80 bg-white/75 backdrop-blur">
                <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-6 py-5 lg:px-8">
                    <a href="{{ url('/') }}" class="flex items-center gap-3 text-sm font-semibold tracking-[0.22em] text-slate-950 uppercase">
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-cyan-200 bg-cyan-50 text-cyan-700">C</span>
                        <span>{{ config('app.name', 'Code') }}</span>
                    </a>

                    <nav class="flex items-center gap-5 text-sm text-slate-600">
                        <a href="{{ url('/') }}" class="transition hover:text-slate-950">Home</a>
                        <a href="{{ route('blog.index') }}" class="transition hover:text-slate-950">Blog</a>
                    </nav>
                </div>
            </header>

            <main class="mx-auto w-full max-w-6xl px-6 py-10 lg:px-8 lg:py-16">
                @yield('content')
            </main>
        </div>
    </body>
</html>
