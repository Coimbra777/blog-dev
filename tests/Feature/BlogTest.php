<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class BlogTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_blog_index_lists_portuguese_posts(): void
    {
        config(['app.env' => 'production']);

        $this->get('/blog')
            ->assertOk()
            ->assertSeeText('Organizando um blog técnico em Laravel sem banco de dados')
            ->assertSeeText('Meu primeiro post')
            ->assertDontSeeText('Organizing a Laravel technical blog without a database');
    }

    public function test_english_blog_index_lists_english_posts(): void
    {
        config(['app.env' => 'production']);

        $this->get('/en/blog')
            ->assertOk()
            ->assertSeeText('Organizing a Laravel technical blog without a database')
            ->assertSeeText('Simple cache and secure Markdown for small blogs')
            ->assertDontSeeText('Meu primeiro post');
    }

    public function test_blog_show_returns_200_for_valid_portuguese_post(): void
    {
        $this->get('/blog/laravel-docker-sem-banco')
            ->assertOk()
            ->assertSeeText('Organizando um blog técnico em Laravel sem banco de dados');
    }

    public function test_english_blog_show_returns_200_for_valid_post(): void
    {
        $this->get('/en/blog/laravel-docker-without-database')
            ->assertOk()
            ->assertSeeText('Organizing a Laravel technical blog without a database');
    }

    public function test_blog_show_returns_404_for_missing_post(): void
    {
        $this->get('/blog/post-inexistente')
            ->assertNotFound()
            ->assertSeeText('Post ou página não encontrado.');
    }

    public function test_english_blog_show_returns_localized_404_for_missing_post(): void
    {
        $this->get('/en/blog/missing-post')
            ->assertNotFound()
            ->assertSeeText('Post or page not found.');
    }

    public function test_portuguese_blog_tag_filters_posts_by_tag(): void
    {
        config(['app.env' => 'production']);

        $this->get('/blog/tag/cache')
            ->assertOk()
            ->assertSeeText('Cache simples e Markdown seguro para blogs pequenos')
            ->assertDontSeeText('Organizando um blog técnico em Laravel sem banco de dados');
    }

    public function test_english_blog_tag_filters_posts_by_tag(): void
    {
        config(['app.env' => 'production']);

        $this->get('/en/blog/tag/security')
            ->assertOk()
            ->assertSeeText('Simple cache and secure Markdown for small blogs')
            ->assertDontSeeText('Organizing a Laravel technical blog without a database');
    }

    public function test_language_switcher_appears_in_layout(): void
    {
        $this->get('/blog')
            ->assertOk()
            ->assertSee(route('home'), false)
            ->assertSee(route('en.blog.index'), false)
            ->assertSee('PT', false)
            ->assertSee('EN', false);
    }

    public function test_post_with_translation_uses_the_correct_language_switch_link(): void
    {
        $this->get('/blog/laravel-docker-sem-banco')
            ->assertOk()
            ->assertSee(route('en.blog.show', 'laravel-docker-without-database'), false);
    }

    public function test_post_without_translation_falls_back_to_the_other_language_listing(): void
    {
        $this->get('/blog/meu-primeiro-post')
            ->assertOk()
            ->assertSee(route('en.blog.index'), false);
    }

    public function test_draft_posts_do_not_appear_in_production(): void
    {
        config(['app.env' => 'production']);

        $this->get('/blog')
            ->assertOk()
            ->assertDontSeeText('Rascunho: observabilidade pragmática para aplicações pequenas');

        $this->get('/blog/rascunho-observabilidade-pragmatica')
            ->assertNotFound();
    }

    public function test_draft_posts_are_visible_in_local_environment(): void
    {
        config(['app.env' => 'local']);

        $this->get('/blog')
            ->assertOk()
            ->assertSeeText('Rascunho: observabilidade pragmática para aplicações pequenas');

        $this->get('/blog/rascunho-observabilidade-pragmatica')
            ->assertOk();
    }

    public function test_unsafe_html_does_not_render_script_tags(): void
    {
        $this->get('/blog/cache-markdown-seguro')
            ->assertOk()
            ->assertDontSee("alert('isto nao deve ser renderizado')", false)
            ->assertDontSeeText('isto nao deve ser renderizado');
    }

    public function test_posts_are_separated_by_locale_directories(): void
    {
        $this->assertTrue(File::exists(resource_path('posts/pt/2026-05-04-laravel-docker.md')));
        $this->assertTrue(File::exists(resource_path('posts/en/2026-05-04-laravel-docker-without-database.md')));
        $this->assertFalse(File::exists(resource_path('posts/2026-05-04-laravel-docker.md')));
    }
}
