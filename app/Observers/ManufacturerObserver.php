<?php

namespace App\Observers;

use App\Models\Manufacturer;
use Illuminate\Support\Facades\Cache;

class ManufacturerObserver
{
    /**
     * Handle the Facilty "created" event.
     */
    final public function created(Manufacturer $manufacturer): void
    {
        $this->forget_manufacturer();
    }

    /**
     * Handle the Facilty "updated" event.
     */
    final public function updated(Manufacturer $manufacturer): void
    {
        $this->forget_manufacturer();
    }

    /**
     * Handle the Facilty "deleted" event.
     */
    final public function deleted(Manufacturer $manufacturer): void
    {
        $this->forget_manufacturer();
    }

    /**
     * Handle the Facilty "restored" event.
     */
    final public function restored(Manufacturer $manufacturer): void
    {
        $this->forget_manufacturer();
    }

    /**
     * Handle the Facilty "force deleted" event.
     */
    final public function forceDeleted(Manufacturer $manufacturer): void
    {
        $this->forget_manufacturer();
    }

    private function forget_manufacturer(): void
    {
        Cache::forget('manufacturers');
    }
}
