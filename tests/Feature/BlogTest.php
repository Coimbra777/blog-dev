<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class BlogTest extends TestCase
{
    private const FIXTURE_PREFIX = '__phpunit_blog_';

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        $this->removeBlogFixtures();
        File::ensureDirectoryExists(resource_path('posts/pt'));
        File::ensureDirectoryExists(resource_path('posts/en'));
        $this->writeCoreFixtures();
    }

    protected function tearDown(): void
    {
        $this->removeBlogFixtures();

        parent::tearDown();
    }

    public function test_blog_index_lists_fixture_posts_in_production(): void
    {
        config(['app.env' => 'production']);

        $this->get('/blog')
            ->assertOk()
            ->assertSeeText('__PHPUnit Alpha Title__')
            ->assertSeeText('__PHPUnit Beta Cache Title__');
    }

    public function test_blog_show_returns_200_for_fixture_post(): void
    {
        $this->get('/blog/phpunit-alpha-post')
            ->assertOk()
            ->assertSeeText('__PHPUnit Alpha Title__')
            ->assertSeeText('Corpo alpha único para asserts.');
    }

    public function test_blog_show_returns_404_for_missing_post(): void
    {
        $this->get('/blog/post-inexistente')
            ->assertNotFound()
            ->assertSeeText('Post ou página não encontrado.');
    }

    public function test_portuguese_blog_tag_filters_posts_by_tag(): void
    {
        config(['app.env' => 'production']);

        $this->get('/blog/tag/cache')
            ->assertOk()
            ->assertSeeText('__PHPUnit Beta Cache Title__')
            ->assertDontSeeText('__PHPUnit Alpha Title__');
    }

    public function test_draft_posts_do_not_appear_in_production(): void
    {
        config(['app.env' => 'production']);

        $this->get('/blog')
            ->assertOk()
            ->assertDontSeeText('__PHPUnit Draft Title__');

        $this->get('/blog/phpunit-draft-post')
            ->assertNotFound();
    }

    public function test_draft_posts_are_visible_in_local_environment(): void
    {
        config(['app.env' => 'local']);

        $this->get('/blog')
            ->assertOk()
            ->assertSeeText('__PHPUnit Draft Title__');

        $this->get('/blog/phpunit-draft-post')
            ->assertOk()
            ->assertSeeText('__PHPUnit Draft Title__');
    }

    public function test_unsafe_html_does_not_render_script_tags(): void
    {
        $this->get('/blog/phpunit-xss-post')
            ->assertOk()
            ->assertDontSee("alert('isto nao deve ser renderizado')", false)
            ->assertDontSeeText('isto nao deve ser renderizado')
            ->assertSeeText('Texto visível PHPUnit XSS.');
    }

    public function test_posts_are_separated_by_locale_directories(): void
    {
        $this->assertTrue(File::isDirectory(resource_path('posts/pt')));
        $this->assertTrue(File::isDirectory(resource_path('posts/en')));
        $this->assertSame([], File::glob(resource_path('posts/*.md')) ?: []);
    }

    public function test_corrupt_markdown_post_is_skipped_without_breaking_index(): void
    {
        File::put(
            resource_path('posts/pt/'.self::FIXTURE_PREFIX.'broken.yaml.md'),
            "---\nthis is: [ broken yaml\n---\n\nBody\n",
        );

        Cache::flush();

        config(['app.env' => 'production']);

        $this->get('/blog')
            ->assertOk()
            ->assertSeeText('__PHPUnit Alpha Title__');
    }

    private function writeCoreFixtures(): void
    {
        File::put(resource_path('posts/pt/'.self::FIXTURE_PREFIX.'alpha.md'), <<<'MD'
---
title: "__PHPUnit Alpha Title__"
slug: "phpunit-alpha-post"
translation_key: "phpunit-alpha"
description: "Resumo alpha phpunit."
date: "2026-01-02"
draft: false
tags:
  - phpunit
  - laravel
---

Corpo alpha único para asserts.

MD);

        File::put(resource_path('posts/pt/'.self::FIXTURE_PREFIX.'beta.md'), <<<'MD'
---
title: "__PHPUnit Beta Cache Title__"
slug: "phpunit-beta-cache-post"
translation_key: "phpunit-beta"
description: "Post só tag cache."
date: "2026-01-01"
draft: false
tags:
  - cache
---

Conteúdo beta.

MD);

        File::put(resource_path('posts/pt/'.self::FIXTURE_PREFIX.'draft.md'), <<<'MD'
---
title: "__PHPUnit Draft Title__"
slug: "phpunit-draft-post"
translation_key: "phpunit-draft"
description: "Rascunho de teste."
date: "2026-01-03"
draft: true
tags:
  - phpunit
---

Rascunho.

MD);

        File::put(resource_path('posts/pt/'.self::FIXTURE_PREFIX.'xss.md'), <<<'MD'
---
title: "__PHPUnit XSS Title__"
slug: "phpunit-xss-post"
translation_key: "phpunit-xss"
description: "Sanitização Markdown."
date: "2026-01-04"
draft: false
tags:
  - seguranca
---

<script>alert('isto nao deve ser renderizado')</script>

Texto visível PHPUnit XSS.

MD);

        File::put(resource_path('posts/en/'.self::FIXTURE_PREFIX.'en_stub.md'), <<<'MD'
---
title: "__PHPUnit EN Stub__"
slug: "phpunit-en-stub"
translation_key: "phpunit-en-stub"
description: "Stub locale dir."
date: "2026-01-01"
draft: false
tags:
  - phpunit
---

Stub.

MD);
    }

    private function removeBlogFixtures(): void
    {
        foreach (['pt', 'en'] as $locale) {
            $dir = resource_path('posts/'.$locale);
            if (! File::isDirectory($dir)) {
                continue;
            }

            foreach (File::glob($dir.'/'.self::FIXTURE_PREFIX.'*.md') ?: [] as $path) {
                File::delete($path);
            }
        }
    }
}
