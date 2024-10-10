<?php

namespace App\Observers;

use App\Models\Warehouse;
use Illuminate\Support\Facades\Cache;

class WarehouseObserver
{
    /**
     * Handle the Facilty "created" event.
     */
    final public function created(Warehouse $warehouse): void
    {
        $this->forget_warehouse();
    }

    /**
     * Handle the Facilty "updated" event.
     */
    final public function updated(Warehouse $warehouse): void
    {
        $this->forget_warehouse();
    }

    /**
     * Handle the Facilty "deleted" event.
     */
    final public function deleted(Warehouse $warehouse): void
    {
        $this->forget_warehouse();
    }

    /**
     * Handle the Facilty "restored" event.
     */
    final public function restored(Warehouse $warehouse): void
    {
        $this->forget_warehouse();
    }

    /**
     * Handle the Facilty "force deleted" event.
     */
    final public function forceDeleted(Warehouse $warehouse): void
    {
        $this->forget_warehouse();
    }

    private function forget_warehouse(): void
    {
        Cache::forget('warehouses');
    }
}
