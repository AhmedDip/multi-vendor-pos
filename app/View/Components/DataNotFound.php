<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DataNotFound extends Component
{
    public int $colspan;
    /**
     * Create a new component instance.
     */
    public function __construct(int $colspan)
    {
        $this->colspan = $colspan;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.data-not-found');
    }
}
