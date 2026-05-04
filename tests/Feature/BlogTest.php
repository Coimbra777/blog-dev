<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class BlogTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_blog_index_returns_200(): void
    {
        $this->get('/blog')
            ->assertOk();
    }

    public function test_blog_lists_published_posts(): void
    {
        config(['app.env' => 'production']);

        $this->get('/blog')
            ->assertOk()
            ->assertSeeText('Cache simples e Markdown seguro para blogs pequenos')
            ->assertSeeText('Organizando um blog técnico em Laravel sem banco de dados')
            ->assertDontSeeText('Rascunho: observabilidade pragmática para aplicações pequenas');
    }

    public function test_blog_show_returns_200_for_valid_post(): void
    {
        $this->get('/blog/laravel-docker-sem-banco')
            ->assertOk()
            ->assertSeeText('Organizando um blog técnico em Laravel sem banco de dados');
    }

    public function test_blog_show_returns_404_for_missing_post(): void
    {
        $this->get('/blog/post-inexistente')
            ->assertNotFound()
            ->assertSeeText('Post ou página não encontrado.');
    }

    public function test_blog_tag_filters_posts_by_tag(): void
    {
        config(['app.env' => 'production']);

        $this->get('/blog/tag/cache')
            ->assertOk()
            ->assertSeeText('Cache simples e Markdown seguro para blogs pequenos')
            ->assertDontSeeText('Organizando um blog técnico em Laravel sem banco de dados');
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
            ->assertDontSee('<script', false)
            ->assertDontSee('</script>', false);
    }
}
