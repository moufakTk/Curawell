<?php

namespace App\Http\Middleware\Language;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class SetLocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Accept-Language', 'en');

        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }

        // 💡 هذا هو المفتاح: لازم تعمل الاثنين
        App::setLocale($locale);               // يستخدم اللغة فورًا
        Config::set('app.locale', $locale);    // يخبر Laravel بالتغيير

        return $next($request);
    }
}
