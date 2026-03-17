<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = (string) session('locale', config('app.locale', 'en'));

        if (! in_array($locale, ['en', 'sw'], true)) {
            $locale = (string) config('app.locale', 'en');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
