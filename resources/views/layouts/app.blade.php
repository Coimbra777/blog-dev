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
    <body class="min-h-screen bg-[#151515] text-[#f9f9f9] antialiased">
        <div class="min-h-screen bg-[radial-gradient(circle_at_top,_rgba(252,142,0,0.08),_transparent_28%),linear-gradient(180deg,_rgba(255,255,255,0.02),_transparent_22%)]">
            <header class="border-b border-white/10">
                <div class="mx-auto w-full max-w-5xl px-6 py-6 lg:px-8">
                    <div class="flex items-center justify-between text-[0.7rem] font-medium uppercase tracking-[0.34em] text-white/45">
                        <span>Gabriel Coimbra</span>
                        <div class="flex items-center gap-2">
                            <span class="text-[#fc8e00]">PT</span>
                            <span>/</span>
                            <span>EN</span>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                        <a href="{{ url('/') }}" class="text-2xl font-semibold tracking-tight text-[#f9f9f9] transition hover:text-[#fc8e00]">
                            Gabriel Coimbra
                        </a>

                        <nav class="flex items-center gap-5 text-sm text-white/70">
                            <a href="{{ url('/') }}" class="transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">Home</a>
                            <a href="{{ route('blog.index') }}" class="transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">Posts</a>
                            <a href="{{ url('/#sobre') }}" class="transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">Sobre</a>
                        </nav>
                    </div>
                </div>
            </header>

            <main class="mx-auto w-full max-w-5xl px-6 py-12 lg:px-8 lg:py-16">
                @yield('content')
            </main>

            <footer class="border-t border-white/10">
                <div class="mx-auto w-full max-w-5xl px-6 py-6 text-sm text-white/45 lg:px-8">
                    © 2026 Gabriel Coimbra. Todos os direitos reservados.
                </div>
            </footer>
        </div>
    </body>
</html>
