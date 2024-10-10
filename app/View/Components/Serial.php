<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Serial extends Component
{
    public int $serial;
    public object $collection;

    /**
     * Create a new component instance.
     */
    public function __construct(int $serial, object $collection)
    {
        $this->serial     = $serial;
        $this->collection = $collection;
    }

    /**
     * Get the view / contents that represent the component.
     */
    final public function render(): View|Closure|string
    {
        return view('components.serial');
    }
}
