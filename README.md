# Blog Técnico com Laravel 12

Este projeto contém um blog técnico em Laravel com posts em Markdown versionados no repositório. O conteúdo é multilíngue por diretório, sem banco de dados para os artigos.

## Rodando localmente

```sh
cp .env.example .env
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
```

Para o frontend:

```sh
npm install
npm run build
```

Se seu container `app` também tiver Node.js, você pode rodar os comandos de frontend dentro dele.

## Rotas do blog

- Português:
  - `/`
  - `/blog`
  - `/blog/{slug}`
  - `/blog/tag/{tag}`
- Inglês:
  - `/en`
  - `/en/blog`
  - `/en/blog/{slug}`
  - `/en/blog/tag/{tag}`

## Estrutura dos posts

```text
resources/posts/
  pt/
  en/
```

Cada idioma possui sua própria listagem. Os posts não são misturados entre PT e EN.

## Como criar um post em português

Crie um arquivo em `resources/posts/pt`:

```md
---
title: "Título do post"
slug: "slug-do-post"
description: "Resumo curto do post"
date: "2026-05-04"
tags: ["laravel", "docker", "backend"]
draft: false
translation_key: "meu-post-traduzivel"
---

# Conteúdo do post

Texto do artigo em Markdown.
```

## Como criar um post em inglês

Crie um arquivo em `resources/posts/en`:

```md
---
title: "Post title"
slug: "post-slug"
description: "Short summary"
date: "2026-05-04"
tags: ["laravel", "docker", "backend"]
draft: false
translation_key: "meu-post-traduzivel"
---

# Post content

English article text.
```

## Como ligar traduções com `translation_key`

- Use o mesmo `translation_key` no arquivo PT e no arquivo EN.
- O seletor `PT / EN` tenta abrir a página equivalente no outro idioma.
- Se não houver tradução para um post, o seletor cai para a listagem do idioma correspondente.

## Regras de publicação

- `draft: false` publica normalmente.
- `draft: true` aparece apenas em ambiente local.
- Em produção, drafts não entram na listagem e retornam `404` no detalhe.

## Publicação

1. Adicione ou edite um arquivo em `resources/posts/pt` ou `resources/posts/en`.
2. Revise o front matter e o conteúdo Markdown.
3. Faça `git add`, `git commit` e `git push`.
4. Após o deploy, o novo post aparecerá automaticamente na rota correspondente ao idioma.

## Cache do blog

Os posts são cacheados com `Cache::remember()` por idioma.

Para limpar manualmente:

```sh
docker compose exec app php artisan cache:clear
```

## Testes

```sh
docker compose run --rm app php artisan test
```

## Build do frontend

```sh
npm run build
```

## Deploy no Render

O blog não exige banco nem Redis para funcionar. Para uma configuração simples no Render, use cache em arquivo.

### Variáveis de ambiente mínimas

```sh
APP_NAME="Gabriel Coimbra"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://seu-dominio.com
CACHE_STORE=file
LOG_CHANNEL=stderr
```

### Build command recomendado

```sh
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Se `npm ci` não puder ser usado por ausência de lock compatível no ambiente, use `npm install`.

### Start command recomendado

```sh
php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
```

### Observações para Render

- O Render fornece a variável `$PORT`.
- As rotas estão prontas para `php artisan route:cache`.
- O conteúdo do blog continua sendo publicado apenas por commit/push dos arquivos Markdown.
