<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormLabel extends Component
{
     public $required;
    public $for;

    public function __construct($required = false, $for = null)
    {
        $this->required = $required;
        $this->for = $for;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-label');
    }
}
