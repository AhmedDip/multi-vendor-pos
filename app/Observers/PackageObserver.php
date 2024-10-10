<?php

namespace App\Observers;

use App\Models\Package;
use Illuminate\Support\Facades\Cache;

class PackageObserver
{
    /**
     * Handle the Facilty "created" event.
     */
    final public function created(Package $package): void
    {
        $this->forget_package();
    }

    /**
     * Handle the Facilty "updated" event.
     */
    final public function updated(Package $package): void
    {
        $this->forget_package();
    }

    /**
     * Handle the Facilty "deleted" event.
     */
    final public function deleted(Package $package): void
    {
        $this->forget_package();
    }

    /**
     * Handle the Facilty "restored" event.
     */
    final public function restored(Package $package): void
    {
        $this->forget_package();
    }

    /**
     * Handle the Facilty "force deleted" event.
     */
    final public function forceDeleted(Package $package): void
    {
        $this->forget_package();
    }

    private function forget_package(): void
    {
        Cache::forget('packages');
    }
}
