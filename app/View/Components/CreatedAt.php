<?php

namespace App\View\Components;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CreatedAt extends Component
{
    public Carbon $created;
    /**
     * Create a new component instance.
     */
    public function __construct(Carbon $created)
    {
        $this->created = $created;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.created-at');
    }
}
