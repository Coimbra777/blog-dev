<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithLocalizedViews;
use App\Support\LocalizedRoute;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use InteractsWithLocalizedViews;

    public function show(Request $request): View
    {
        $locale = LocalizedRoute::normalize((string) $request->route('locale', 'pt'));

        App::setLocale($locale);

        return view('welcome', $this->localizedViewData($locale, [
            'canonical' => route(LocalizedRoute::routeName($locale, 'home')),
            'localeUrls' => [
                'pt' => route('home'),
                // 'en' => route('en.home'), // PT/EN: rotas /en desativadas
            ],
        ]));
    }
}
