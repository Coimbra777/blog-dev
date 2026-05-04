@extends('layouts.app')

@php
    $metaTitle = '404 | '.config('app.name', 'Laravel');
    $metaDescription = 'A página solicitada não foi encontrada.';
@endphp

@section('content')
    <div class="mx-auto max-w-3xl rounded-3xl border border-white/10 bg-[#252525]/70 px-8 py-16 text-center">
        <p class="text-xs font-medium uppercase tracking-[0.32em] text-[#fc8e00]">404</p>
        <h1 class="mt-4 text-4xl font-semibold tracking-tight text-[#f9f9f9] sm:text-5xl">Post ou página não encontrado.</h1>
        <p class="mx-auto mt-4 max-w-xl text-base leading-8 text-white/58">
            O conteúdo solicitado pode ter sido removido, renomeado ou ainda não foi publicado.
        </p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('blog.index') }}" class="rounded-full bg-[#fc8e00] px-5 py-3 text-sm font-medium text-[#151515] transition hover:bg-[#ff9d21] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                Ir para Posts
            </a>
            <a href="{{ url('/') }}" class="rounded-full border border-white/10 bg-transparent px-5 py-3 text-sm font-medium text-white/72 transition hover:border-[#fc8e00]/40 hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                Voltar para a home
            </a>
        </div>
    </div>
@endsection
