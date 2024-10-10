<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ValidationError extends Component
{

    public string|null $error;

    /**
     * Create a new component instance.
     */
    public function __construct(string|null $errors)
    {
        $this->error = $errors;
    }

    /**
     * Get the view / contents that represent the component.
     */
    final public function render(): View|Closure|string
    {
        return view('components.validation-error');
    }
}
