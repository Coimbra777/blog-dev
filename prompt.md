Ajuste o blog Laravel para suportar alternância real entre Português e Inglês e deixe o projeto pronto para deploy no Render.

Contexto:

- Projeto Laravel 12 + PHP 8.4 + Docker
- Blog Markdown já implementado
- Interface já refinada com tema escuro e identidade Gabriel Coimbra
- Header já exibe “PT / EN”, mas ainda precisa virar uma funcionalidade real
- Posts ficam em resources/posts
- Não usamos banco de dados para posts

Objetivos:

1. Implementar suporte real a PT/EN.
2. Manter a experiência simples e intuitiva.
3. Preparar o projeto para deploy no Render.
4. Não quebrar a engine atual de Markdown, cache e testes.

Parte 1 — Internacionalização PT/EN

Implementar idioma usando prefixo de rota:

Rotas esperadas:

- `/` → home em português
- `/en` → home em inglês
- `/blog` → posts em português
- `/en/blog` → posts em inglês
- `/blog/{slug}` → post em português
- `/en/blog/{slug}` → post em inglês
- `/blog/tag/{tag}` → tag em português
- `/en/blog/tag/{tag}` → tag em inglês

Regras:

- Português é o idioma padrão.
- Inglês usa prefixo `/en`.
- O seletor “PT / EN” no header deve trocar para a mesma página no outro idioma quando possível.
  Exemplos:
    - Em `/blog` → EN aponta para `/en/blog`
    - Em `/en/blog` → PT aponta para `/blog`
    - Em `/blog/meu-post` → EN aponta para `/en/blog/my-post`, se existir tradução
    - Se não existir tradução equivalente, apontar para a listagem do idioma correspondente.
- Destacar visualmente o idioma ativo.

Parte 2 — Estrutura dos posts

Ajustar a estrutura para suportar posts por idioma:

resources/posts/
pt/
2026-05-04-meu-post.md
en/
2026-05-04-my-post.md

Cada post deve ter front matter:

---

title: "Título"
slug: "meu-post"
description: "Descrição"
date: "2026-05-04"
tags: ["laravel", "docker"]
draft: false
translation_key: "laravel-docker-post"

---

Regras:

- `translation_key` conecta o post em português com sua versão em inglês.
- Se um post não tiver tradução, ele deve aparecer normalmente apenas no idioma dele.
- Não misturar posts PT e EN na mesma listagem.
- `/blog` mostra apenas posts em `resources/posts/pt`
- `/en/blog` mostra apenas posts em `resources/posts/en`

Parte 3 — Textos da interface

Traduzir textos fixos da interface.

PT:

- Home
- Posts
- Sobre
- Postagens recentes
- Voltar para Posts
- Posts com a tag:
- Nenhum post encontrado
- Todos os direitos reservados

EN:

- Home
- Posts
- About
- Recent posts
- Back to Posts
- Posts tagged:
- No posts found
- All rights reserved

Pode usar arquivos de tradução Laravel em:

- `lang/pt/blog.php`
- `lang/en/blog.php`

Ou outra abordagem simples, desde que fique organizada e fácil de manter.

Parte 4 — Datas

Formatar datas conforme idioma:

- PT: `4 de Maio, 2026`
- EN: `May 4, 2026`

Usar Carbon/Intl quando apropriado.
Garantir que funcione em produção no Render.

Parte 5 — Service/Controller

Atualizar `BlogPostService` para:

- receber idioma (`pt` ou `en`)
- ler posts do diretório correto
- aplicar cache separado por idioma
- buscar post por slug dentro do idioma correto
- buscar posts por tag dentro do idioma correto
- encontrar tradução equivalente via `translation_key`

Atualizar `BlogController` para:

- lidar com rotas PT e EN
- passar locale para views
- passar URLs PT/EN para o layout
- manter controllers leves

Parte 6 — Views

Atualizar:

- `resources/views/layouts/app.blade.php`
- `resources/views/welcome.blade.php`
- `resources/views/blog/index.blade.php`
- `resources/views/blog/show.blade.php`
- `resources/views/blog/tag.blade.php`
- `resources/views/errors/404.blade.php`

Requisitos visuais:

- Manter a paleta:
    - #fc8e00
    - #151515
    - #252525
    - #f9f9f9
- Manter o visual atual
- Apenas tornar PT/EN funcional
- Header deve continuar simples e bonito
- Idioma ativo deve ficar destacado
- Links devem preservar boa UX

Parte 7 — Migração dos posts existentes

Mover posts existentes para:

resources/posts/pt/

Criar pelo menos 1 post equivalente em inglês em:

resources/posts/en/

Usar `translation_key` nos pares traduzidos.

Não apagar posts existentes sem necessidade.

Parte 8 — Deploy no Render

Preparar o projeto para deploy no Render.

Criar ou ajustar arquivos necessários para facilitar deploy:

Opção recomendada:

- Criar `render.yaml` se fizer sentido para Blueprint
- Ou documentar claramente deploy manual no README

Garantir que o Render consiga executar:

- composer install --no-dev --optimize-autoloader
- npm ci ou npm install
- npm run build
- php artisan config:cache
- php artisan route:cache
- php artisan view:cache
- php artisan serve --host=0.0.0.0 --port=$PORT

Importante:

- Render fornece a variável `$PORT`
- Não fixar porta 10000 se puder usar `$PORT`
- Não exigir banco para o blog
- Não exigir Redis
- Usar CACHE_STORE=file para produção simples
- Garantir APP_ENV=production e APP_DEBUG=false documentados

Variáveis mínimas no README:

- APP_NAME
- APP_ENV=production
- APP_KEY
- APP_DEBUG=false
- APP_URL
- CACHE_STORE=file
- LOG_CHANNEL=stderr

Start command recomendado:
php artisan serve --host=0.0.0.0 --port=${PORT:-10000}

Build command recomendado:
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache

Parte 9 — Testes

Atualizar ou criar testes Feature para:

- `/blog` lista posts PT
- `/en/blog` lista posts EN
- `/blog/{slug}` mostra post PT
- `/en/blog/{slug}` mostra post EN
- seletor de idioma aparece no layout
- post com tradução usa link correto
- post sem tradução cai para listagem do outro idioma
- drafts continuam ocultos em produção
- tags funcionam em PT e EN
- 404 continua funcionando

Rodar:

- docker compose run --rm app php artisan test
- npm run build

Parte 10 — README

Atualizar README com:

- Como rodar localmente
- Como criar post em português
- Como criar post em inglês
- Como ligar traduções com `translation_key`
- Como publicar post
- Como limpar cache
- Como rodar testes
- Como subir no Render
- Variáveis de ambiente do Render
- Build command
- Start command

Cuidados:

- Não reescrever a engine inteira sem necessidade
- Não adicionar banco de dados
- Não adicionar CMS/admin
- Não quebrar posts atuais
- Não adicionar bibliotecas pesadas
- Não copiar o site de referência
- Manter código simples, limpo, testável e fácil de manter

Entregável final:
Ao terminar, gere um resumo com:

1. Arquivos criados
2. Arquivos alterados
3. Novas rotas PT/EN
4. Como criar posts PT e EN
5. Como funciona o `translation_key`
6. Como configurar no Render
7. Comandos executados e resultados
