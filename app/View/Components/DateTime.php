<?php

namespace App\View\Components;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DateTime extends Component
{
    public Carbon $created;
    public Carbon $updated;
    /**
     * Create a new component instance.
     */
    public function __construct(Carbon $created, Carbon $updated)
    {
        $this->created = $created;
        $this->updated = $updated;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.date-time');
    }
}
