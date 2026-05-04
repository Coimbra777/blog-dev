---
title: "Organizing a Laravel technical blog without a database"
slug: "laravel-docker-without-database"
description: "A simple approach for publishing Markdown articles versioned directly in the repository."
date: "2026-05-04"
tags: ["laravel", "docker", "backend"]
draft: false
translation_key: "laravel-docker-post"
---

# Organizing technical content inside the repository

Publishing Markdown posts inside the project itself is a practical solution when the goal is to keep the workflow simple, auditable, and close to the code.

## Why this works well

- Git history shows every revision of the article
- deployment carries the content together with the application
- there is no coupling with tables, admin panels, or a CMS

## Minimal structure

A folder such as `resources/posts` already solves the initial problem.

```php
Route::get('/en/blog', [BlogController::class, 'index']);
Route::get('/en/blog/{slug}', [BlogController::class, 'show']);
```

## Next step

Once the basic flow is stable, it becomes easy to evolve into RSS, sitemap, pagination, and search.
