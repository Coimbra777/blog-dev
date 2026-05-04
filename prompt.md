Implemente um blog técnico completo na minha base Laravel 12 + PHP 8.4 + Docker, usando as tecnologias e padrões atuais do projeto.

Contexto da base:

- Projeto Laravel 12 com PHP 8.4
- Docker já configurado
- Vite + Tailwind CSS v4 já presentes
- Atualmente a base está próxima do skeleton padrão do Laravel
- Existe apenas a página inicial padrão
- Não quero usar banco de dados para os posts neste momento
- Os posts devem ser arquivos Markdown versionados no GitHub

Referência visual:
Quero uma interface simples, técnica, limpa e intuitiva, parecida com este estilo de blog:
https://luizmachado.dev/posts.html

Importante:

- Não copiar layout, textos ou identidade visual do site de referência.
- Usar apenas como inspiração: lista de posts clara, datas, títulos, resumos, tags, navegação simples e leitura agradável.

Objetivo:
Criar um blog técnico dentro do Laravel onde eu possa adicionar novos posts manualmente criando arquivos `.md` em `resources/posts`.

Fluxo esperado:

1. Eu crio um arquivo Markdown em `resources/posts`
2. O Laravel lê automaticamente esse arquivo
3. O post aparece em `/blog`
4. O detalhe aparece em `/blog/{slug}`
5. As tags aparecem em `/blog/tag/{tag}`
6. Sem precisar alterar código para publicar um novo post

Estrutura dos posts:
Cada post deve usar front matter YAML no topo:

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

Requisitos funcionais:

- Criar rota GET `/blog` para listar posts publicados
- Criar rota GET `/blog/{slug}` para exibir post individual
- Criar rota GET `/blog/tag/{tag}` para listar posts por tag
- Ordenar posts por data decrescente
- Ocultar posts com `draft: true` em produção
- Permitir visualizar drafts apenas em ambiente local
- Exibir título, descrição, data, tags e tempo estimado de leitura
- Criar página 404 quando o slug não existir
- Criar página/listagem vazia amigável caso não existam posts
- Criar pelo menos 2 posts de exemplo em `resources/posts`

Requisitos técnicos:

- Usar `league/commonmark` para converter Markdown em HTML
- Usar `symfony/yaml` para ler o front matter
- Declarar explicitamente essas dependências no `composer.json`, mesmo que já estejam no lock
- Criar uma camada separada para leitura/parsing dos posts
- Não colocar lógica pesada no controller
- Usar cache para evitar reler todos os arquivos a cada request
- Usar `Cache::remember` com TTL simples
- Não usar banco de dados para posts
- Não criar painel administrativo
- Não usar CMS

Arquitetura sugerida:
Criar algo simples, organizado e evolutivo:

app/
Services/
Blog/
BlogPostService.php
BlogPostData.php

resources/
posts/
2026-05-04-exemplo-laravel-docker.md
2026-05-05-exemplo-filamentos-backend.md

resources/views/
layouts/
app.blade.php
blog/
index.blade.php
show.blade.php
tag.blade.php

app/Http/Controllers/
BlogController.php

Segurança:

- Configurar CommonMark para bloquear ou remover HTML bruto inseguro
- Não permitir links inseguros
- Escapar metadados nas views usando `{{ }}`
- Renderizar apenas o HTML convertido do Markdown em uma área controlada
- Evitar XSS em title, description, tags e conteúdo

Interface:
Criar uma interface responsiva e profissional usando Blade + Tailwind.

A interface deve conter:

- Layout base reutilizável
- Header simples com nome/marca e navegação
- Link para Home e Blog
- Página `/blog` com:
    - título da página
    - descrição curta
    - lista de posts
    - tags visíveis
    - datas formatadas
    - cards limpos e clicáveis
- Página `/blog/{slug}` com:
    - botão/link de voltar
    - título grande
    - descrição
    - data
    - tempo de leitura
    - tags
    - conteúdo Markdown bem formatado
    - largura confortável para leitura
- Página `/blog/tag/{tag}` com:
    - título indicando a tag filtrada
    - lista de posts daquela tag
    - link para voltar ao blog

Visual desejado:

- Minimalista
- Técnico
- Limpo
- Bom espaçamento
- Tipografia legível
- Cards suaves
- Responsivo mobile/desktop
- Sem excesso visual
- Parecido com um portfólio/blog de desenvolvedor backend

Estilo:

- Usar Tailwind CSS v4 já existente
- Evitar bibliotecas extras de UI
- Não usar Bootstrap
- Não depender de JavaScript para funcionalidades principais
- A experiência deve funcionar bem mesmo com HTML renderizado pelo servidor

SEO básico:
Adicionar nas views:

- title dinâmico
- meta description
- canonical quando fizer sentido
- estrutura HTML semântica
- headings corretos

Opcional, se for simples:

- Criar rota `/feed.xml` para RSS
- Criar rota `/sitemap.xml`
- Criar helper para leitura de tags disponíveis

Testes:
Criar testes Feature cobrindo:

- `/blog` retorna 200
- `/blog` lista posts publicados
- `/blog/{slug}` retorna 200 para post válido
- `/blog/{slug}` retorna 404 para post inexistente
- `/blog/tag/{tag}` filtra posts pela tag
- posts com `draft: true` não aparecem em produção
- Markdown com HTML inseguro não executa/renderiza script

Documentação:
Atualizar o README.md com:

- Como criar um novo post
- Exemplo de front matter
- Onde salvar os arquivos Markdown
- Como rodar localmente com Docker
- Como limpar cache do blog
- Como rodar testes
- Como publicar um post via commit/push

Comandos esperados:

- composer require league/commonmark symfony/yaml
- php artisan test
- npm install
- npm run build, se necessário

Cuidados:

- Não alterar desnecessariamente arquivos não relacionados
- Não quebrar a página inicial existente
- Não criar banco/tabelas para posts
- Não criar autenticação
- Não criar painel admin
- Não adicionar dependências desnecessárias
- Manter código simples, legível e testável

Entregável final:
Ao terminar, gere um resumo com:

1. Arquivos criados
2. Arquivos alterados
3. Rotas criadas
4. Como adicionar um novo post
5. Como rodar os testes
6. Pontos futuros de evolução
