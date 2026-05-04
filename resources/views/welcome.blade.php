@php
    $metaTitle = 'Gabriel Coimbra | Backend, Laravel e Arquitetura';
    $metaDescription = 'Desenvolvedor Backend focado em Laravel, Node.js, APIs, Docker e arquitetura de software.';
    $canonical = url('/');
@endphp

@extends('layouts.app')

@section('content')
    <section class="space-y-24">
        <div class="grid gap-14 lg:grid-cols-[minmax(0,1fr)_18rem] lg:items-end">
            <div class="space-y-6">
                <p class="text-xs font-medium uppercase tracking-[0.32em] text-[#fc8e00]">Backend Engineer</p>
                <div class="space-y-5">
                    <h1 class="max-w-3xl text-5xl font-semibold tracking-tight text-[#f9f9f9] sm:text-6xl">
                        Gabriel Coimbra
                    </h1>
                    <p class="max-w-2xl text-base leading-8 text-white/62 sm:text-lg">
                        Desenvolvedor Backend focado em Laravel, Node.js, APIs, Docker e arquitetura de software.
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ route('blog.index') }}" class="rounded-full bg-[#fc8e00] px-6 py-3 text-sm font-medium text-[#151515] transition hover:bg-[#ff9d21] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                        Ver Posts
                    </a>
                    <a href="#sobre" class="text-sm font-medium text-white/65 transition hover:text-[#fc8e00] focus:outline-none focus:ring-2 focus:ring-[#fc8e00]/60 focus:ring-offset-2 focus:ring-offset-[#151515]">
                        Sobre
                    </a>
                </div>
            </div>

            <div class="rounded-3xl border border-white/10 bg-[#252525]/70 p-6">
                <p class="text-xs font-medium uppercase tracking-[0.28em] text-white/45">Foco</p>
                <ul class="mt-5 space-y-3 text-sm leading-7 text-white/62">
                    <li>APIs REST e integrações</li>
                    <li>Containerização com Docker</li>
                    <li>Arquitetura simples e sustentável</li>
                    <li>Qualidade de código e entrega contínua</li>
                </ul>
            </div>
        </div>

        <section id="sobre" class="grid gap-10 border-t border-white/10 pt-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,0.85fr)]">
            <div class="space-y-4">
                <p class="text-xs font-medium uppercase tracking-[0.32em] text-[#fc8e00]">Sobre</p>
                <h2 class="text-3xl font-semibold tracking-tight text-[#f9f9f9]">
                    Engenharia de software com ênfase em clareza, manutenção e execução.
                </h2>
            </div>

            <div class="space-y-5 text-base leading-8 text-white/62">
                <p>
                    Este espaço reúne artigos técnicos, aprendizados de projeto e anotações sobre decisões reais de backend.
                </p>
                <p>
                    A proposta é manter uma escrita direta, sem excesso visual, com foco em arquitetura, APIs, Laravel e infraestrutura de desenvolvimento.
                </p>
            </div>
        </section>
    </section>
@endsection
