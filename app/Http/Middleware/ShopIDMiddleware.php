<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShopIDMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    final public function handle(Request $request, Closure $next): Response
    {
        // if (!$request->header('shop-id')) {
        //     return response()->json(['message' => 'shop id not found'], 401);
        // }
        // $request->merge(['shop_id' => $request->header('shop-id')]);
        // return $next($request);

        $routeAction = $request->route()->getActionName();
        if (str_ends_with($routeAction, '@show') || str_ends_with($routeAction, '@destroy')){
            return $next($request);
        }
        if (!$request->header('shop-id')) {
            return response()->json(['message' => 'shop id not found'], 401);
        }
    
        $request->merge(['shop_id' => $request->header('shop-id')]);
    
        return $next($request);
    }
}
