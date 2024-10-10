<?php

namespace App\Observers;

use App\Models\Menu;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

class MenuObserver
{
    /**
     * Handle the Menu "created" event.
     */
    final public function created(Menu $menu): void
    {
       Cache::forget('sidebar_menus');
    }

    /**
     * Handle the Menu "updated" event.
     */
    final public function updated(Menu $menu): void
    {
        Cache::forget('sidebar_menus');
    }

    /**
     * Handle the Menu "deleted" event.
     */
    final public function deleted(Menu $menu): void
    {
        Cache::forget('sidebar_menus');
    }

    /**
     * Handle the Menu "restored" event.
     */
    final public function restored(Menu $menu): void
    {
        Cache::forget('sidebar_menus');
    }

    /**
     * Handle the Menu "force deleted" event.
     */
    final public function forceDeleted(Menu $menu): void
    {
        Cache::forget('sidebar_menus');
    }
}
