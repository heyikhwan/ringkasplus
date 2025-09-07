<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormRadio extends Component
{
    public $label;
    public $name;
    public $required;
    public $defaultValue;
    public $options;
    public $help;

    public function __construct($label = null, $name = null, $required = false, $defaultValue = null, $help = null, $options = [])
    {
        $this->label = $label;
        $this->name = $name;
        $this->required = $required;
        $this->defaultValue = $defaultValue;
        $this->options = $options;
        $this->help = $help;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-radio');
    }

    public function errorKey()
    {
        return str_replace(['[', ']'], ['.', ''], $this->name);
    }
}
