Ajuste a interface do blog Laravel para ficar minimalista, técnica e elegante, inspirada no layout de listagem de posts do luizmachado.dev/posts.html, mas usando identidade própria de Gabriel Coimbra.

Contexto:

- Projeto Laravel 12 + PHP 8.4 + Docker
- Blog Markdown já implementado
- Rotas existentes:
    - GET /blog
    - GET /blog/{slug}
    - GET /blog/tag/{tag}
- Views existentes:
    - resources/views/layouts/app.blade.php
    - resources/views/blog/index.blade.php
    - resources/views/blog/show.blade.php
    - resources/views/blog/tag.blade.php
- Tailwind CSS v4 já configurado
- Posts ficam em resources/posts
- Não alterar a engine do blog, parsing, cache ou testes, exceto se necessário para ajustar UI

Objetivo:
Refinar a interface para um blog técnico simples e bonito, com foco em leitura, clareza e portfólio profissional.

Referência visual:
Usar como inspiração a estrutura:

- topo com nome/marca
- seletor de idioma visual “PT EN”
- navegação: Home, Posts, Sobre
- título “Postagens recentes”
- lista vertical de posts
- cada post com título, data, resumo e tags
- footer simples

Importante:

- NÃO copiar textos, identidade, classes ou código do site de referência
- Criar uma identidade própria para Gabriel Coimbra
- Usar a paleta:
    - laranja principal: #fc8e00
    - fundo escuro: #151515
    - superfície/card: #252525
    - texto claro: #f9f9f9

Direção visual:

- Tema escuro
- Minimalista
- Profissional
- Técnico
- Boa leitura em desktop e mobile
- Sem excesso de cards pesados
- Aparência de blog de desenvolvedor backend
- Usar detalhes em laranja para links, tags, hover, bordas ou pequenos indicadores
- Espaçamento confortável
- Tipografia legível
- Layout centralizado com largura máxima agradável

Alterações desejadas:

1. Layout base
   Atualizar `resources/views/layouts/app.blade.php` para:

- fundo #151515
- texto #f9f9f9
- container central com max-width adequado
- header no topo com:
    - “Gabriel Coimbra” à esquerda
    - “PT EN” discreto no topo/direita
    - navegação com Home, Posts, Sobre
- estado hover nos links usando #fc8e00
- footer:
    - “© 2026 Gabriel Coimbra. Todos os direitos reservados.”
- manter suporte a title e meta description dinâmicos

2. Página /blog
   Atualizar `resources/views/blog/index.blade.php` para ficar parecida em estrutura com:

Gabriel Coimbra
PT EN
Home Posts Sobre

Postagens recentes

[Post 1]
Título do post
Data formatada
Descrição
tags juntas em estilo técnico

[Post 2]
...

Requisitos da listagem:

- Título principal: “Postagens recentes”
- Cada post deve exibir:
    - título clicável
    - data em português brasileiro, exemplo: “4 de Maio, 2026”
    - descrição
    - tags
- Tags devem aparecer como texto pequeno em laranja ou chips discretos
- O card/list item deve ser limpo, com borda sutil ou separador
- Hover no título e item deve melhorar a percepção de clique
- Manter responsivo

3. Página /blog/{slug}
   Atualizar `resources/views/blog/show.blade.php` para:

- link discreto “← Voltar para Posts”
- título grande do post
- data formatada
- descrição
- tags
- conteúdo Markdown com boa tipografia
- headings com contraste correto
- links em #fc8e00
- blocos de código bem legíveis em fundo #252525
- largura confortável para leitura

4. Página /blog/tag/{tag}
   Atualizar `resources/views/blog/tag.blade.php` para:

- título: “Posts com a tag: {tag}”
- usar o mesmo estilo da listagem principal
- link para voltar aos posts

5. Home e navegação
   Se existir apenas a welcome padrão do Laravel:

- Ajustar `resources/views/welcome.blade.php` para uma home simples do portfólio
- Não precisa criar algo complexo
- Home deve ter:
    - nome Gabriel Coimbra
    - descrição curta: “Desenvolvedor Backend focado em Laravel, Node.js, APIs, Docker e arquitetura de software.”
    - CTA para /blog
- Manter visual consistente com a paleta

6. Tailwind / CSS
   Atualizar `resources/css/app.css` se necessário para:

- definir estilos globais úteis
- melhorar renderização de conteúdo Markdown
- estilizar `.prose` manualmente se não houver plugin typography
- não instalar bibliotecas extras desnecessárias
- não usar Bootstrap
- não adicionar JavaScript desnecessário

7. UX
   Aplicar boas práticas:

- contraste acessível
- espaçamento consistente
- foco em legibilidade
- hover/focus visíveis
- links identificáveis
- mobile-first
- não poluir a tela
- não usar animações exageradas

8. Deploy Render
   Preparar ajustes mínimos para deploy no Render, sem quebrar Docker local:

- Verificar se o app consegue rodar com variáveis:
    - APP_ENV=production
    - APP_DEBUG=false
    - APP_URL
- Não adicionar banco para posts
- Não depender de Node em runtime
- Garantir que `npm run build` gere assets corretamente
- Se houver necessidade de documentação, atualizar README com seção “Deploy no Render”
- Não criar configuração complexa se não for necessária

9. Testes
   Após ajustes:

- Rodar `docker compose run --rm app php artisan test`
- Rodar `npm run build`
- Se testes quebraram por mudança visual, ajustar apenas o necessário
- Não remover testes existentes

10. Resultado esperado
    Ao final, entregar resumo com:

- arquivos alterados
- mudanças visuais aplicadas
- como rodar localmente
- como adicionar novo post
- como preparar deploy no Render
- comandos executados e resultado

Cuidados:

- Não mexer na lógica central de parsing Markdown se não for necessário
- Não remover posts existentes
- Não remover testes
- Não copiar o site de referência literalmente
- Usar a referência apenas como inspiração estrutural
- Manter código simples, limpo e fácil de evoluir
