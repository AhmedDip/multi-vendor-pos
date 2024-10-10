<?php

namespace App\Observers;

use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Cache;

class PaymentMethodObserver
{
    /**
     * Handle the Facilty "created" event.
     */
    final public function created(PaymentMethod $payment_method): void
    {
        $this->forget_payment_method();
    }

    /**
     * Handle the Facilty "updated" event.
     */
    final public function updated(PaymentMethod $payment_method): void
    {
        $this->forget_payment_method();
    }

    /**
     * Handle the Facilty "deleted" event.
     */
    final public function deleted(PaymentMethod $payment_method): void
    {
        $this->forget_payment_method();
    }

    /**
     * Handle the Facilty "restored" event.
     */
    final public function restored(PaymentMethod $payment_method): void
    {
        $this->forget_payment_method();
    }

    /**
     * Handle the Facilty "force deleted" event.
     */
    final public function forceDeleted(PaymentMethod $payment_method): void
    {
        $this->forget_payment_method();
    }

    private function forget_payment_method(): void
    {
        Cache::forget('payment_methods');
    }
}
