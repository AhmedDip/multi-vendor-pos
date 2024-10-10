<?php

namespace App\Manager\UI;

use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class MenuManager
{
    /**
     * @return mixed
     */
    final public static function get_menus(): mixed
    {
        return cache()->rememberForever('sidebar_menus', function () {
            return Menu::query()
                ->orderBy('sort_order')
                ->whereNull('menu_id')
                ->select(['id', 'menu_id', 'name', 'route', 'icon', 'sort_order', 'status'])
                ->with(['sub_menus', 'sub_menus.sub_menus'])
                ->where('status', Menu::STATUS_ACTIVE)
                ->get();
        });
    }

    /**
     * @param object $menus
     * @return bool
     */
    final public static function check_permission_sub_menu(object $menus): bool
    {
        return true;
        $permissions = cache()->rememberForever('permissions', function () {
            return Permission::query()
                ->select(['name'])
                ->get();
        });
        $permission = false;
        foreach ($menus as $menu) {
            if (!empty($menu->route) && Route::has($menu->route) && $permissions->firstWhere('name', $menu->route) && Auth::user()->hasPermissionTo($menu->route)) {
                $permission = true;
                break;
            }
        }
        return $permission;
    }
}
