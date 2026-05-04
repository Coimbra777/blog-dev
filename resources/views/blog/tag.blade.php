@php
    $metaTitle = 'Tag #'.$tag.' | '.config('app.name', 'Laravel');
    $metaDescription = 'Posts técnicos filtrados pela tag #'.$tag.'.';
@endphp

@extends('layouts.app')

@section('content')
    <section class="space-y-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="space-y-3">
                <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900">
                    <span aria-hidden="true">←</span>
                    <span>Voltar para o blog</span>
                </a>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.32em] text-cyan-700">Tag</p>
                    <h1 class="mt-2 text-4xl font-semibold tracking-tight text-slate-950">
                        Posts marcados com #{{ $tag }}
                    </h1>
                    <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600">
                        Filtro aplicado sobre os artigos publicados que compartilham o mesmo contexto técnico.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-white/80 p-5 shadow-sm shadow-slate-200/50">
                <p class="text-sm font-medium text-slate-500">Explorar outras tags</p>
                <div class="mt-3 flex max-w-sm flex-wrap gap-2">
                    @foreach ($availableTags as $availableTag)
                        <a href="{{ route('blog.tag', $availableTag) }}" class="rounded-full border px-3 py-1 text-sm transition {{ $availableTag === $tag ? 'border-cyan-300 bg-cyan-50 text-cyan-800' : 'border-slate-200 bg-slate-50 text-slate-700 hover:border-cyan-300 hover:text-cyan-800' }}">
                            #{{ $availableTag }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        @if ($posts->isEmpty())
            <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 px-8 py-16 text-center shadow-sm">
                <h2 class="text-2xl font-semibold text-slate-950">Nenhum post encontrado para esta tag.</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-600">
                    Publique um artigo com a tag <code class="rounded bg-slate-100 px-2 py-1 text-slate-800">{{ $tag }}</code> para vê-lo aparecer aqui.
                </p>
            </div>
        @else
            <div class="grid gap-5">
                @foreach ($posts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="group rounded-[2rem] border border-slate-200/80 bg-white/85 p-6 shadow-sm shadow-slate-200/60 transition hover:-translate-y-0.5 hover:border-cyan-300 hover:shadow-lg hover:shadow-cyan-100/60 sm:p-8">
                        <article class="space-y-4">
                            <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500">
                                <span>{{ $post->formattedDate() }}</span>
                                <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                <span>{{ $post->readingTimeMinutes }} min de leitura</span>
                            </div>

                            <div class="space-y-2">
                                <h2 class="text-2xl font-semibold tracking-tight text-slate-950 transition group-hover:text-cyan-800">
                                    {{ $post->title }}
                                </h2>
                                <p class="max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                                    {{ $post->description }}
                                </p>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
@endsection
