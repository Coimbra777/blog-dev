---
title: "Cache simples e Markdown seguro para blogs pequenos"
slug: "cache-markdown-seguro"
description: "Como reduzir leitura repetida de arquivos e renderizar Markdown com uma configuração mais segura."
date: "2026-05-05"
tags: ["cache", "markdown", "seguranca"]
draft: false
translation_key: "secure-markdown-cache"
---

# Cache e segurança andam juntos

Quando os posts vivem em arquivos, o custo principal deixa de ser a consulta ao banco e passa a ser a leitura e o parsing dos arquivos em cada request.

## Cache por fingerprint

Uma estratégia eficiente é montar uma chave baseada em nome de arquivo e data de modificação. Assim, novos commits invalidam naturalmente o cache.

## Markdown seguro

HTML cru não deve ser confiado.

<script>alert('isto nao deve ser renderizado')</script>

Com `league/commonmark`, basta bloquear HTML bruto e links inseguros para evitar que o conteúdo processado execute código arbitrário.

## Resultado

Você mantém a escrita em Markdown e entrega HTML previsível no servidor, sem depender de JavaScript no cliente.
