<?php

namespace App\Manager\AccessControl;

use Illuminate\Routing\Controllers\Middleware;

trait AccessControlTrait
{
    /**
     * @return array
     */

    public static function middleware(): array
    {
        $methods     = [
            'index', 'create', 'store', 'show', 'edit', 'update', 'destroy',
        ];
        $middlewares = [];
        foreach ($methods as $method) {
            // $middlewares[] = new Middleware(middleware: 'permission:' . self::$route . '.' . $method, only: [$method]);
        }
        return $middlewares;
    }
}
