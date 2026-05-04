# Blog Técnico com Laravel 12

Este projeto contém um blog técnico em Laravel com posts em Markdown versionados no repositório. O conteúdo é multilíngue por diretório, sem banco de dados para os artigos.

## Rodando localmente

O `.env.example` segue defaults compatíveis com produção (SQLite em `/tmp`). Em desenvolvimento com Docker na pasta do projeto, você pode usar, por exemplo, `DB_DATABASE=database/database.sqlite` e criar o arquivo com `touch database/database.sqlite`.

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

## Deploy no Render (sem banco)

O conteúdo do blog vem só dos arquivos Markdown no repositório. Ainda assim, o Laravel 12 espera uma conexão de banco **válida** (`config/database.php`): se `DB_CONNECTION` vier vazio, inválido ou `null`, o framework pode falhar com **Undefined array key "driver"** ou tentar abrir um SQLite inexistente.

A solução usada aqui é **SQLite em arquivo temporário** (`/tmp/database.sqlite`), criado na imagem de produção, **sem rodar migrations** e sem MySQL/Redis:

- **Motivo:** satisfazer o default do Laravel e qualquer código que resolva `DB::connection()` sem precisar de serviço externo nem tabelas.
- **Limitação:** `/tmp` é efêmero; não use esse SQLite para dados de negócio (o blog não grava artigos no banco).

### Imagem de produção

Use o arquivo `Dockerfile.render` no serviço Web do Render. Ele:

- instala `pdo_sqlite`;
- executa `touch /tmp/database.sqlite`;
- define `ENV` padrão (`DB_CONNECTION`, `DB_DATABASE`, `SESSION_DRIVER`, `CACHE_STORE`, `QUEUE_CONNECTION`);
- no start: `config:cache`, `route:cache`, `view:cache` e `php artisan serve` na porta `PORT` do Render.

### Variáveis de ambiente recomendadas no painel

Defina pelo menos:

```sh
APP_NAME="Seu nome ou marca"
APP_ENV=production
APP_KEY=base64:...        # obrigatório em produção
APP_DEBUG=false
APP_URL=https://seu-app.onrender.com

DB_CONNECTION=sqlite
DB_DATABASE=/tmp/database.sqlite

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

APP_MAINTENANCE_DRIVER=file
APP_MAINTENANCE_STORE=file

LOG_CHANNEL=stderr
```

Não defina `DB_CONNECTION=null`. Valores vazios ou a string `null` são normalizados para `sqlite` em `config/database.php`, mas o caminho do arquivo (`DB_DATABASE`) deve continuar apontando para um arquivo que exista (a imagem já cria `/tmp/database.sqlite`).

### Sem migrations no deploy

O conteúdo do blog não depende de tabelas. **Não** use `php artisan migrate` no build nem no start deste projeto em produção.

### Build e start “na mão” (sem Docker)

Se preferir Native Environment no Render em vez de Docker:

**Build**

```sh
composer install --no-dev --optimize-autoloader
npm ci
npm run build
touch /tmp/database.sqlite
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Start**

```sh
php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
```

### Redeploy

Após alterar variáveis ou o `Dockerfile.render`, faça **Manual Deploy** (ou push na branch conectada) para rebuild. O SQLite em `/tmp` é recriado na nova imagem; não é necessário migrar nada.
