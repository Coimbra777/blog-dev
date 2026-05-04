@php
    $metaTitle = 'Blog | '.config('app.name', 'Laravel');
    $metaDescription = 'Artigos técnicos publicados em Markdown sobre Laravel, Docker, backend e arquitetura.';
    $canonical = route('blog.index');
@endphp

@extends('layouts.app')

@section('content')
    <section class="space-y-8">
        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_18rem] lg:items-end">
            <div class="space-y-4">
                <p class="text-xs font-semibold uppercase tracking-[0.32em] text-cyan-700">Blog</p>
                <div class="space-y-3">
                    <h1 class="max-w-3xl text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                        Notas técnicas sobre backend, arquitetura e entrega pragmática.
                    </h1>
                    <p class="max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">
                        Posts versionados em Markdown, publicados direto pelo repositório e renderizados no servidor.
                    </p>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200/80 bg-white/80 p-6 shadow-sm shadow-slate-200/50">
                <p class="text-sm font-medium text-slate-500">Tags disponíveis</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    @forelse ($availableTags as $tag)
                        <a href="{{ route('blog.tag', $tag) }}" class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-sm text-slate-700 transition hover:border-cyan-300 hover:text-cyan-800">
                            #{{ $tag }}
                        </a>
                    @empty
                        <span class="text-sm text-slate-500">Nenhuma tag publicada ainda.</span>
                    @endforelse
                </div>
            </div>
        </div>

        @if ($posts->isEmpty())
            <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white/70 px-8 py-16 text-center shadow-sm">
                <h2 class="text-2xl font-semibold text-slate-950">Nenhum post publicado ainda.</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-600">
                    Adicione arquivos Markdown em <code class="rounded bg-slate-100 px-2 py-1 text-slate-800">resources/posts</code> para popular esta página automaticamente.
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
                                @if ($post->draft)
                                    <span class="rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">Draft</span>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <h2 class="text-2xl font-semibold tracking-tight text-slate-950 transition group-hover:text-cyan-800">
                                    {{ $post->title }}
                                </h2>
                                <p class="max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                                    {{ $post->description }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @foreach ($post->tags as $tag)
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium uppercase tracking-[0.18em] text-slate-600">
                                        {{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
@endsection
