<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Pagination extends Component
{
    public object $collection;

    /**
     * Create a new component instance.
     */
    public function __construct(object $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Get the view / contents that represent the component.
     */
    final public function render(): View|Closure|string
    {
        return view('components.pagination');
    }
}
