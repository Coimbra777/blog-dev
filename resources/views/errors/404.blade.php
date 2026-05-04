@extends('layouts.app')

@php
    $metaTitle = '404 | '.config('app.name', 'Laravel');
    $metaDescription = 'A página solicitada não foi encontrada.';
@endphp

@section('content')
    <div class="mx-auto max-w-3xl rounded-[2rem] border border-slate-200/80 bg-white/90 px-8 py-16 text-center shadow-sm shadow-slate-200/60">
        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-cyan-700">404</p>
        <h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl">Post ou página não encontrado.</h1>
        <p class="mx-auto mt-4 max-w-xl text-base leading-7 text-slate-600">
            O conteúdo solicitado pode ter sido removido, renomeado ou ainda não foi publicado.
        </p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('blog.index') }}" class="rounded-full bg-slate-950 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800">
                Ir para o blog
            </a>
            <a href="{{ url('/') }}" class="rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:text-slate-950">
                Voltar para a home
            </a>
        </div>
    </div>
@endsection
