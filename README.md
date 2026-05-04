# Blog Técnico com Laravel 12

Este projeto contém um blog técnico em Laravel com posts versionados em Markdown dentro do próprio repositório. Não existe banco de dados para os artigos: o conteúdo é lido a partir de arquivos em `resources/posts`.

## Rodando localmente com Docker

```sh
cp .env.example .env
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app npm install
```

Para desenvolvimento com assets em watch, rode o Vite no container ou localmente conforme sua configuração.

## Estrutura do blog

- Lista de posts: `/blog`
- Detalhe do post: `/blog/{slug}`
- Filtro por tag: `/blog/tag/{tag}`
- Arquivos Markdown: `resources/posts/*.md`

## Como criar um novo post

1. Crie um novo arquivo `.md` em `resources/posts`.
2. Adicione o front matter YAML no topo.
3. Faça commit e push do arquivo para publicar junto com a aplicação.

Exemplo:

```md
---
title: "Título do post"
slug: "slug-do-post"
description: "Resumo curto do post"
date: "2026-05-04"
tags: ["laravel", "docker", "backend"]
draft: false
---

# Conteúdo do post

Texto do artigo em Markdown.
```

## Regras de publicação

- Posts com `draft: false` aparecem normalmente.
- Posts com `draft: true` ficam visíveis apenas em ambiente local.
- Em produção, drafts não aparecem na listagem e retornam `404` no detalhe.

## Cache do blog

Os posts são cacheados com `Cache::remember()` por alguns minutos para evitar parsing completo a cada request.

Para limpar o cache manualmente:

```sh
docker compose exec app php artisan cache:clear
```

## Testes

Rode os testes de feature e unidade com:

```sh
docker compose exec app php artisan test
```

## Build de frontend

Para gerar os assets de produção:

```sh
docker compose exec app npm run build
```

## Fluxo de publicação via commit/push

1. Adicione ou edite um arquivo em `resources/posts`.
2. Revise o front matter e o conteúdo Markdown.
3. Faça `git add`, `git commit` e `git push`.
4. Após o deploy, o novo post aparecerá automaticamente em `/blog`.
