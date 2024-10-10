<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ActivityLog extends Component
{
    public object|null $logs;

    /**
     * Create a new component instance.
     */
    public function __construct(object|null $logs)
    {
        $this->logs = $logs;
    }

    /**
     * Get the view / contents that represent the component.
     */
    final public function render(): View|Closure|string
    {
        return view('components.activity-log');
    }
}
