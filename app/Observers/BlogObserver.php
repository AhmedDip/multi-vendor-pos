<?php

namespace App\Observers;

use App\Models\Blog;
use Illuminate\Support\Facades\Cache;

class BlogObserver
{
    /**
     * Handle the Facilty "created" event.
     */
    final public function created(Blog $blog): void
    {
        $this->forget_blog();
    }

    /**
     * Handle the Facilty "updated" event.
     */
    final public function updated(Blog $blog): void
    {
        $this->forget_blog();
    }

    /**
     * Handle the Facilty "deleted" event.
     */
    final public function deleted(Blog $blog): void
    {
        $this->forget_blog();
    }

    /**
     * Handle the Facilty "restored" event.
     */
    final public function restored(Blog $blog): void
    {
        $this->forget_blog();
    }

    /**
     * Handle the Facilty "force deleted" event.
     */
    final public function forceDeleted(Blog $blog): void
    {
        $this->forget_blog();
    }

    private function forget_blog(): void
    {
        Cache::forget('blogs');
    }
}
