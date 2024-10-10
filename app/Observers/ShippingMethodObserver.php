<?php

namespace App\Observers;

use App\Models\ShippingMethod;
use Illuminate\Support\Facades\Cache;

class ShippingMethodObserver
{
    /**
     * Handle the Facilty "created" event.
     */
    final public function created(ShippingMethod $shipping_method): void
    {
        $this->forget_shipping_method();
    }

    /**
     * Handle the Facilty "updated" event.
     */
    final public function updated(ShippingMethod $shipping_method): void
    {
        $this->forget_shipping_method();
    }

    /**
     * Handle the Facilty "deleted" event.
     */
    final public function deleted(ShippingMethod $shipping_method): void
    {
        $this->forget_shipping_method();
    }

    /**
     * Handle the Facilty "restored" event.
     */
    final public function restored(ShippingMethod $shipping_method): void
    {
        $this->forget_shipping_method();
    }

    /**
     * Handle the Facilty "force deleted" event.
     */
    final public function forceDeleted(ShippingMethod $shipping_method): void
    {
        $this->forget_shipping_method();
    }

    private function forget_shipping_method(): void
    {
        Cache::forget('shipping_methods');
    }
}
