<?php

namespace App\Observers;

use App\Models\BlogCategory;
use Illuminate\Support\Facades\Cache;

class BlogCategoryObserver
{
    /**
     * Handle the Facilty "created" event.
     */
    final public function created(BlogCategory $blog_category): void
    {
        $this->forget_blog_category();
    }

    /**
     * Handle the Facilty "updated" event.
     */
    final public function updated(BlogCategory $blog_category): void
    {
        $this->forget_blog_category();
    }

    /**
     * Handle the Facilty "deleted" event.
     */
    final public function deleted(BlogCategory $blog_category): void
    {
        $this->forget_blog_category();
    }

    /**
     * Handle the Facilty "restored" event.
     */
    final public function restored(BlogCategory $blog_category): void
    {
        $this->forget_blog_category();
    }

    /**
     * Handle the Facilty "force deleted" event.
     */
    final public function forceDeleted(BlogCategory $blog_category): void
    {
        $this->forget_blog_category();
    }

    private function forget_blog_category(): void
    {
        Cache::forget('blogcategories');
    }
}
