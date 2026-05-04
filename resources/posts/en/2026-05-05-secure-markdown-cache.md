---
title: "Simple cache and secure Markdown for small blogs"
slug: "secure-markdown-cache"
description: "How to reduce repeated file reads and render Markdown with a safer configuration."
date: "2026-05-05"
tags: ["cache", "markdown", "security"]
draft: false
translation_key: "secure-markdown-cache"
---

# Cache and security go together

When posts live in files, the main cost is no longer a database query but reading and parsing those files on every request.

## Fingerprint-based cache

An efficient strategy is to build a cache key from the filename and its modification date. That way, new commits naturally invalidate the cache.

## Safe Markdown

Raw HTML should not be trusted.

<script>alert('this should not be rendered')</script>

With `league/commonmark`, blocking raw HTML and unsafe links is enough to avoid executing arbitrary code in rendered content.

## Result

You keep Markdown authoring and still deliver predictable server-rendered HTML without depending on client-side JavaScript.
