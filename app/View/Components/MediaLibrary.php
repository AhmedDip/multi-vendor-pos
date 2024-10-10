<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MediaLibrary extends Component
{
    public $uniqueid;
    public $inputid;
    public $inputname;
    public $multiple;
    public $displayid;
    public $displaycolumn;
    public $preview;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $uniqueid,
        $inputid = '#media-library-image-input',
        $inputname = 'photo',
        $multiple = true,
        $displayid = '#media-library-preview-small-img',
        $displaycolumn = 3,
        $preview = true
    )
    {
        $this->uniqueid      = $uniqueid;
        $this->inputid       = $inputid;
        $this->inputname     = $inputname;
        $this->multiple      = $multiple;
        $this->displayid     = $displayid;
        $this->displaycolumn = $displaycolumn;
        $this->preview       = $preview;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.media-library');
    }
}
