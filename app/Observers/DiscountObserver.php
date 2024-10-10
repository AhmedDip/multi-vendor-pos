<?php

namespace App\Observers;

use App\Models\Discount;
use Illuminate\Support\Facades\Cache;

class DiscountObserver
{
    /**
     * Handle the Facilty "created" event.
     */
    final public function created(Discount $discount): void
    {
        $this->forget_discount();
    }

    /**
     * Handle the Facilty "updated" event.
     */
    final public function updated(Discount $discount): void
    {
        $this->forget_discount();
    }

    /**
     * Handle the Facilty "deleted" event.
     */
    final public function deleted(Discount $discount): void
    {
        $this->forget_discount();
    }

    /**
     * Handle the Facilty "restored" event.
     */
    final public function restored(Discount $discount): void
    {
        $this->forget_discount();
    }

    /**
     * Handle the Facilty "force deleted" event.
     */
    final public function forceDeleted(Discount $discount): void
    {
        $this->forget_discount();
    }

    private function forget_discount(): void
    {
        Cache::forget('discounts');
    }
}
