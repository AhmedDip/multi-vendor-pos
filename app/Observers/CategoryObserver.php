<?php

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryObserver
{
        /**
     * Handle the Facilty "created" event.
     */
    final public function created(Category $category): void
    {
        $this->forget_category();
    }

    /**
     * Handle the Facilty "updated" event.
     */
    final public function updated(Category $category): void
    {
        $this->forget_category();
    }

    /**
     * Handle the Facilty "deleted" event.
     */
    final public function deleted(Category $category): void
    {
        $this->forget_category();
    }

    /**
     * Handle the Facilty "restored" event.
     */
    final public function restored(Category $category): void
    {
        $this->forget_category();
    }

    /**
     * Handle the Facilty "force deleted" event.
     */
    final public function forceDeleted(Category $category): void
    {
        $this->forget_category();
    }

    private function forget_category(): void
    {
        Cache::forget('categories');
    }
}
