@php
    $metaTitle = $post->title.' | '.config('app.name', 'Laravel');
    $metaDescription = $post->description;
@endphp

@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl space-y-10">
        <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-white/55 transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
            <span aria-hidden="true">←</span>
            <span>Voltar para Posts</span>
        </a>

        <article class="space-y-10">
            <header class="space-y-6 border-b border-white/10 pb-10">
                <p class="text-xs font-medium uppercase tracking-[0.32em] text-[#fc8e00]">Post</p>

                <div class="space-y-4">
                    <h1 class="text-4xl font-semibold tracking-tight text-[#f9f9f9] sm:text-5xl">
                        {{ $post->title }}
                    </h1>
                    <p class="max-w-3xl text-base leading-8 text-white/62 sm:text-lg">
                        {{ $post->description }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-x-3 gap-y-2 text-sm text-white/45">
                    <span>{{ $post->date->locale('pt_BR')->translatedFormat('j \\d\\e F, Y') }}</span>
                    <span class="h-1 w-1 rounded-full bg-white/20"></span>
                    <span>{{ $post->readingTimeMinutes }} min de leitura</span>
                    @if ($post->draft)
                        <span class="rounded-full border border-[#fc8e00]/30 bg-[#fc8e00]/10 px-2.5 py-1 text-[0.7rem] font-medium uppercase tracking-[0.16em] text-[#fc8e00]">Draft</span>
                    @endif
                </div>

                <div class="flex flex-wrap gap-2">
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag) }}" class="text-xs font-medium uppercase tracking-[0.2em] text-[#fc8e00]/90 transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
            </header>

            <div class="blog-prose">
                {!! $post->html !!}
            </div>
        </article>
    </div>
@endsection
