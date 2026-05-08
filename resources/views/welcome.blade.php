@php
    $metaTitle = __('blog.site_title');
    $metaDescription = __('blog.site_description');
    $canonical = $homeUrl;
    $contactUrl = $contactUrl ?? $homeUrl . '#contato';
    $contacts = array_values(array_filter([
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
    ], static fn (array $contact): bool => filled($contact['href'])));
@endphp

@extends('layouts.app')

@section('content')
    <section class="space-y-24">
        <div class="grid gap-14 lg:grid-cols-[minmax(0,1fr)_18rem] lg:items-end">
            <div class="space-y-6">
                <p class="text-xs font-medium uppercase tracking-[0.32em] text-[#fc8e00]">{{ __('blog.home.eyebrow') }}</p>
                <div class="space-y-5">
                    <h1 class="max-w-3xl text-5xl font-semibold tracking-tight text-[#f9f9f9] sm:text-6xl">
                        {{ __('blog.home.headline') }}
                    </h1>
                    <p class="max-w-2xl text-base leading-8 text-white/62 sm:text-lg">
                        {{ __('blog.home.description') }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ $blogIndexUrl }}" class="rounded-full bg-[#fc8e00] px-6 py-3 text-sm font-medium text-[#151515] transition hover:bg-[#ff9d21] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                        {{ __('blog.home.cta_posts') }}
                    </a>
                    <a href="{{ $aboutUrl }}" class="text-sm font-medium text-white/65 transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                        {{ __('blog.home.about_link') }}
                    </a>
                    <a href="{{ $contactUrl }}" class="text-sm font-medium text-white/65 transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                        {{ __('blog.nav.contact') }}
                    </a>
                </div>
            </div>

            <div class="rounded-3xl border border-white/10 bg-[#252525]/70 p-6">
                <p class="text-xs font-medium uppercase tracking-[0.28em] text-white/45">{{ __('blog.home.focus_title') }}</p>
                <ul class="mt-5 space-y-3 text-sm leading-7 text-white/62">
                    @foreach (__('blog.home.focus_items') as $focusItem)
                        <li>{{ $focusItem }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <section id="sobre" class="grid gap-10 border-t border-white/10 pt-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,0.85fr)]">
            <div class="space-y-4">
                <p class="text-xs font-medium uppercase tracking-[0.32em] text-[#fc8e00]">{{ __('blog.home.about_eyebrow') }}</p>
                <h2 class="text-3xl font-semibold tracking-tight text-[#f9f9f9]">
                    {{ __('blog.home.about_title') }}
                </h2>
            </div>

            <div class="space-y-5 text-base leading-8 text-white/62">
                <p>{{ __('blog.home.about_paragraph_1') }}</p>
                <p>{{ __('blog.home.about_paragraph_2') }}</p>
            </div>
        </section>

        <section id="contato" class="grid gap-10 border-t border-white/10 pt-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,0.95fr)]">
            <div class="space-y-4">
                <p class="text-xs font-medium uppercase tracking-[0.32em] text-[#fc8e00]">{{ __('blog.contact.eyebrow') }}</p>
                <h2 class="text-3xl font-semibold tracking-tight text-[#f9f9f9]">
                    {{ __('blog.contact.title') }}
                </h2>
                <p class="max-w-xl text-base leading-8 text-white/62">
                    {{ __('blog.contact.description') }}
                </p>
            </div>

            <div class="rounded-3xl border border-white/10 bg-white/[0.03] p-6 sm:p-8">
                <p class="text-xs font-medium uppercase tracking-[0.28em] text-white/45">{{ __('blog.contact.list_title') }}</p>
                <div class="mt-6 grid gap-4">
                    @forelse ($contacts as $contact)
                        <a href="{{ $contact['href'] }}"
                            class="rounded-2xl border border-white/10 bg-[#252525]/70 px-5 py-4 transition hover:border-[#fc8e00]/40 hover:text-[#fc8e00]">
                            <span class="block text-xs font-medium uppercase tracking-[0.24em] text-white/40">{{ $contact['label'] }}</span>
                            <span class="mt-2 block text-sm break-all text-white/75">{{ $contact['value'] }}</span>
                        </a>
                    @empty
                        <div class="rounded-2xl border border-dashed border-white/10 px-5 py-4 text-sm leading-7 text-white/45">
                            {{ __('blog.contact.empty') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </section>
@endsection
