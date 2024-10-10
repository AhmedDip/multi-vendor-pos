<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    final public function handle(Request $request, Closure $next): Response
    {
        if (Cache::has('language')) {
            app()->setLocale(Cache::get('language'));
        } else {
            app()->setLocale('en');
        }
        return $next($request);
    }
}
