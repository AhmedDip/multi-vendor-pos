<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ImageUpload extends Component
{
    public string $name;
    public string|null $photo;

    /**
     * Create a new component instance.
     */
    public function __construct(string $name, string|null $photo = null)
    {
        $this->name = $name;
        $this->photo = $photo;
    }

    /**
     * Get the view / contents that represent the component.
     */
    final public function render(): View|Closure|string
    {
        return view('components.image-upload');
    }
}
