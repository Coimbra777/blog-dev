---
title: "Organizando um blog técnico em Laravel sem banco de dados"
slug: "laravel-docker-sem-banco"
description: "Uma abordagem simples para publicar artigos em Markdown versionados no repositório."
date: "2026-05-04"
tags: ["laravel", "docker", "backend"]
draft: false
translation_key: "laravel-docker-post"
---

# Organizando conteúdo técnico no repositório

Publicar posts em Markdown dentro do próprio projeto é uma solução prática quando o objetivo é manter o fluxo simples, auditável e próximo do código.

## Por que isso funciona bem

- o histórico do Git mostra cada revisão do artigo
- o deploy já transporta o conteúdo junto com a aplicação
- não existe acoplamento com tabela, painel ou CMS

## Estrutura mínima

Uma pasta como `resources/posts` já resolve o problema inicial.

```php
Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{slug}', [BlogController::class, 'show']);
```

## Próximo passo

Depois que o fluxo básico estiver estável, fica fácil evoluir para RSS, sitemap, paginação e busca.
