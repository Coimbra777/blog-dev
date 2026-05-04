<?php

namespace App\Http\Controllers;

use App\Services\Blog\BlogPostService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function __construct(
        private readonly BlogPostService $blogPosts,
    ) {
    }

    public function index(): View
    {
        return view('blog.index', [
            'posts' => $this->blogPosts->all(),
            'availableTags' => $this->blogPosts->availableTags(),
        ]);
    }

    public function show(string $slug): View
    {
        $post = $this->blogPosts->findBySlug($slug);

        abort_unless($post !== null, 404);

        return view('blog.show', [
            'post' => $post,
            'canonical' => route('blog.show', $post->slug),
        ]);
    }

    public function tag(string $tag): View
    {
        $normalizedTag = Str::of($tag)->trim()->lower()->value();

        return view('blog.tag', [
            'tag' => $normalizedTag,
            'posts' => $this->blogPosts->byTag($normalizedTag),
            'availableTags' => $this->blogPosts->availableTags(),
            'canonical' => route('blog.tag', $normalizedTag),
        ]);
    }
}
