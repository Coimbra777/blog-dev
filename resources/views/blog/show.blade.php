@php
    $metaTitle = $post->title.' | '.config('app.name', 'Laravel');
    $metaDescription = $post->description;
@endphp

@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl space-y-8">
        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900">
            <span aria-hidden="true">←</span>
            <span>Voltar para o blog</span>
        </a>

        <article class="rounded-[2rem] border border-slate-200/80 bg-white/90 p-6 shadow-sm shadow-slate-200/60 sm:p-10">
            <header class="space-y-6 border-b border-slate-200 pb-8">
                <p class="text-xs font-semibold uppercase tracking-[0.32em] text-cyan-700">Post</p>

                <div class="space-y-4">
                    <h1 class="text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">
                        {{ $post->title }}
                    </h1>
                    <p class="max-w-3xl text-base leading-7 text-slate-600 sm:text-lg">
                        {{ $post->description }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500">
                    <span>{{ $post->formattedDate() }}</span>
                    <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                    <span>{{ $post->readingTimeMinutes }} min de leitura</span>
                    @if ($post->draft)
                        <span class="rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">Draft</span>
                    @endif
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag) }}" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium uppercase tracking-[0.18em] text-slate-600 transition hover:bg-cyan-50 hover:text-cyan-800">
                            {{ $tag }}
                        </a>
                    @endforeach
                </div>
            </header>

            <div class="blog-prose mt-10">
                {!! $post->html !!}
            </div>
        </article>
    </div>
@endsection
