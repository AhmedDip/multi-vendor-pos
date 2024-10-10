<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Inactive extends Component
{
    public string|null $title;

    /**
     * Create a new component instance.
     */
    public function __construct(string|null $title = '')
    {
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    final public function render(): View|Closure|string
    {
        return view('components.inactive');
    }
}
