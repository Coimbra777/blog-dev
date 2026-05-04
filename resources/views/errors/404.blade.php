@extends('layouts.app')

@php
    use App\Support\LocalizedRoute;

    $currentLocale = $currentLocale ?? LocalizedRoute::normalize(app()->getLocale());
    $metaTitle = '404 | '.config('app.name', 'Laravel');
    $metaDescription = __('blog.404.description');
    $blogIndexUrl = $blogIndexUrl ?? route(LocalizedRoute::routeName($currentLocale, 'blog.index'));
    $homeUrl = $homeUrl ?? route(LocalizedRoute::routeName($currentLocale, 'home'));
@endphp

@section('content')
    <div class="mx-auto max-w-3xl rounded-3xl border border-white/10 bg-[#252525]/70 px-8 py-16 text-center">
        <p class="text-xs font-medium uppercase tracking-[0.32em] text-[#fc8e00]">404</p>
        <h1 class="mt-4 text-4xl font-semibold tracking-tight text-[#f9f9f9] sm:text-5xl">{{ __('blog.404.title') }}</h1>
        <p class="mx-auto mt-4 max-w-xl text-base leading-8 text-white/58">
            {{ __('blog.404.description') }}
        </p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ $blogIndexUrl }}" class="rounded-full bg-[#fc8e00] px-5 py-3 text-sm font-medium text-[#151515] transition hover:bg-[#ff9d21] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                {{ __('blog.404.go_posts') }}
            </a>
            <a href="{{ $homeUrl }}" class="rounded-full border border-white/10 bg-transparent px-5 py-3 text-sm font-medium text-white/72 transition hover:border-[#fc8e00]/40 hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                {{ __('blog.404.go_home') }}
            </a>
        </div>
    </div>
@endsection
