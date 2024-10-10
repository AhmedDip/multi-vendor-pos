<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ToolTip extends Component
{
    public string $title;
    public string $position;

    /**
     * Create a new component instance.
     */
    public function __construct(string $title = 'title', string $position = 'top')
    {
        $this->title    = $title;
        $this->position = $position;
    }

    /**
     * Get the view / contents that represent the component.
     */
    final public function render(): View|Closure|string
    {
        return view('components.tool-tip');
    }
}
