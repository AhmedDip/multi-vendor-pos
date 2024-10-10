<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    final public function handle(Request $request, Closure $next, $roles)
    {
        if (!Auth::check() || !in_array(Auth::user()->role->name, $roles)) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
