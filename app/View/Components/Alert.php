<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public string $class;
    public string $message;

    /**
     * Create a new component instance.
     */
    public function __construct(string $class, string $message)
    {
        $this->class   = $class;
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     */
    final public function render(): View|Closure|string
    {
        return view('components.alert');
    }
}
