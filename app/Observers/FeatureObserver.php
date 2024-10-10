<?php

namespace App\Observers;

use App\Models\Feature;
use Illuminate\Support\Facades\Cache;

class FeatureObserver
{
    /**
     * Handle the Facilty "created" event.
     */
    final public function created(Feature $feature): void
    {
        $this->forget_feature();
    }

    /**
     * Handle the Facilty "updated" event.
     */
    final public function updated(Feature $feature): void
    {
        $this->forget_feature();
    }

    /**
     * Handle the Facilty "deleted" event.
     */
    final public function deleted(Feature $feature): void
    {
        $this->forget_feature();
    }

    /**
     * Handle the Facilty "restored" event.
     */
    final public function restored(Feature $feature): void
    {
        $this->forget_feature();
    }

    /**
     * Handle the Facilty "force deleted" event.
     */
    final public function forceDeleted(Feature $feature): void
    {
        $this->forget_feature();
    }

    private function forget_feature(): void
    {
        Cache::forget('features');
    }
}
