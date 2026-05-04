@php
    $metaTitle = 'Tag #'.$tag.' | '.config('app.name', 'Laravel');
    $metaDescription = 'Posts técnicos filtrados pela tag #'.$tag.'.';
@endphp

@extends('layouts.app')

@section('content')
    <section class="space-y-10">
        <div class="space-y-5 border-b border-white/10 pb-10">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-white/55 transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                <span aria-hidden="true">←</span>
                <span>Voltar para Posts</span>
            </a>

            <div class="space-y-3">
                <p class="text-xs font-medium uppercase tracking-[0.32em] text-[#fc8e00]">Tag</p>
                <h1 class="text-4xl font-semibold tracking-tight text-[#f9f9f9] sm:text-5xl">
                    Posts com a tag: {{ $tag }}
                </h1>
                <p class="max-w-2xl text-base leading-8 text-white/62">
                    Conteúdo filtrado por assunto, mantendo a mesma estrutura limpa da listagem principal.
                </p>
            </div>

            <div class="flex flex-wrap gap-2 pt-2">
                @foreach ($availableTags as $availableTag)
                    <a href="{{ route('blog.tag', $availableTag) }}" class="inline-flex items-center rounded-full border px-3 py-1.5 text-xs font-medium uppercase tracking-[0.18em] transition focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515] {{ $availableTag === $tag ? 'border-[#fc8e00] bg-[#fc8e00]/10 text-[#fc8e00]' : 'border-white/10 bg-[#252525] text-white/60 hover:border-[#fc8e00]/40 hover:text-[#fc8e00]' }}">
                        #{{ $availableTag }}
                    </a>
                @endforeach
            </div>
        </div>

        @if ($posts->isEmpty())
            <div class="rounded-3xl border border-dashed border-white/15 bg-[#252525]/60 px-8 py-16 text-center">
                <h2 class="text-2xl font-semibold text-[#f9f9f9]">Nenhum post encontrado para esta tag.</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-white/55">
                    Publique um artigo com a tag <code class="rounded bg-white/5 px-2 py-1 font-mono text-[#f9f9f9]">{{ $tag }}</code> para vê-lo aparecer aqui.
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
                                @foreach ($post->tags as $postTag)
                                    <a href="{{ route('blog.tag', $postTag) }}" class="text-xs font-medium uppercase tracking-[0.2em] text-[#fc8e00]/90 transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                                        #{{ $postTag }}
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
