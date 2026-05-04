@php
    $metaTitle = 'Blog | '.config('app.name', 'Laravel');
    $metaDescription = 'Artigos técnicos publicados em Markdown sobre Laravel, Docker, backend e arquitetura.';
    $canonical = route('blog.index');
@endphp

@extends('layouts.app')

@section('content')
    <section class="space-y-12">
        <div class="space-y-5 border-b border-white/10 pb-10">
            <p class="text-xs font-medium uppercase tracking-[0.32em] text-[#fc8e00]">Posts</p>
            <div class="space-y-4">
                <h1 class="text-4xl font-semibold tracking-tight text-[#f9f9f9] sm:text-5xl">
                    Postagens recentes
                </h1>
                <p class="max-w-2xl text-base leading-8 text-white/62 sm:text-lg">
                    Artigos sobre Laravel, APIs, Docker e decisões de arquitetura com foco em backend pragmático.
                </p>
            </div>

            <div class="flex flex-wrap gap-2 pt-2">
                @forelse ($availableTags as $tag)
                    <a href="{{ route('blog.tag', $tag) }}" class="inline-flex items-center rounded-full border border-[#fc8e00]/30 bg-[#252525] px-3 py-1.5 text-xs font-medium uppercase tracking-[0.18em] text-[#fc8e00] transition hover:border-[#fc8e00] hover:bg-[#fc8e00]/10 focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                        #{{ $tag }}
                    </a>
                @empty
                    <span class="text-sm text-white/45">Nenhuma tag publicada ainda.</span>
                @endforelse
            </div>
        </div>

        @if ($posts->isEmpty())
            <div class="rounded-3xl border border-dashed border-white/15 bg-[#252525]/60 px-8 py-16 text-center">
                <h2 class="text-2xl font-semibold text-[#f9f9f9]">Nenhum post publicado ainda.</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-white/55">
                    Adicione arquivos Markdown em <code class="rounded bg-white/5 px-2 py-1 font-mono text-[#f9f9f9]">resources/posts</code> para popular esta página automaticamente.
                </p>
            </div>
        @else
            <div class="space-y-2">
                @foreach ($posts as $post)
                    <article class="group border-b border-white/10 py-7 first:pt-0 last:border-b-0">
                        <div class="space-y-4">
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-white/45">
                                <span>{{ $post->date->locale('pt_BR')->translatedFormat('j \\d\\e F, Y') }}</span>
                                <span class="h-1 w-1 rounded-full bg-white/20"></span>
                                <span>{{ $post->readingTimeMinutes }} min de leitura</span>
                                @if ($post->draft)
                                    <span class="rounded-full border border-[#fc8e00]/30 bg-[#fc8e00]/10 px-2.5 py-1 text-[0.7rem] font-medium uppercase tracking-[0.16em] text-[#fc8e00]">Draft</span>
                                @endif
                            </div>

                            <div class="space-y-3">
                                <h2 class="text-2xl font-semibold tracking-tight text-[#f9f9f9] sm:text-3xl">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                                        {{ $post->title }}
                                    </a>
                                </h2>
                                <p class="max-w-3xl text-sm leading-8 text-white/62 sm:text-base">
                                    {{ $post->description }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @foreach ($post->tags as $tag)
                                    <a href="{{ route('blog.tag', $tag) }}" class="text-xs font-medium uppercase tracking-[0.2em] text-[#fc8e00]/90 transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                                        #{{ $tag }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
