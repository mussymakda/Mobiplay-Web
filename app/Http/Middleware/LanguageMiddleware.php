<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if session has a locale value
        if (Session::has('locale')) {
            // Force the app to use session-stored locale
            $locale = Session::get('locale');
            App::setLocale($locale);
            Log::info('Locale set to: ' . $locale); // Log the session locale being set
        } else {
            // If no language in session, set the default
            $defaultLocale = config('app.locale', 'en');
            App::setLocale($defaultLocale);
            Session::put('locale', $defaultLocale); // Store default in session
            Log::info('Locale set to default: ' . $defaultLocale); // Log default locale
        }

        return $next($request);
    }
}