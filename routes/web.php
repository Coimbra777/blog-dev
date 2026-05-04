<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'show')
        ->defaults('locale', 'pt')
        ->name('home');
});

Route::controller(BlogController::class)->group(function () {
    Route::get('/blog', 'index')
        ->defaults('locale', 'pt')
        ->name('blog.index');
    Route::get('/blog/tag/{tag}', 'tag')
        ->defaults('locale', 'pt')
        ->name('blog.tag');
    Route::get('/blog/{slug}', 'show')
        ->defaults('locale', 'pt')
        ->name('blog.show');
});

/*
|--------------------------------------------------------------------------
| Rotas em inglês (/en/...) — desativadas (PT/EN comentado)
|--------------------------------------------------------------------------
|
Route::prefix('en')
    ->as('en.')
    ->group(function (): void {
        Route::controller(HomeController::class)->group(function () {
            Route::get('/', 'show')
                ->defaults('locale', 'en')
                ->name('home');
        });

        Route::controller(BlogController::class)->group(function () {
            Route::get('/blog', 'index')
                ->defaults('locale', 'en')
                ->name('blog.index');
            Route::get('/blog/tag/{tag}', 'tag')
                ->defaults('locale', 'en')
                ->name('blog.tag');
            Route::get('/blog/{slug}', 'show')
                ->defaults('locale', 'en')
                ->name('blog.show');
        });
    });
|
*/
