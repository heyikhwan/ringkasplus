<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormInputFile extends Component
{
    public $label;
    public $name;
    public $id;
    public $value;
    public $help;
    public $accept;
    public $required;
    public $class;

    public function __construct($label = null, $name = null, $id = null, $value = null, $help = null, $accept = null, $required = false, $class = null)
    {
        $this->label = $label;
        $this->name = $name;
        $this->id = $id ?? $name;
        $this->value = $value;
        $this->help = $help;
        $this->accept = $accept;
        $this->required = $required;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-input-file');
    }

    public function errorKey()
    {
        return str_replace(['[', ']'], ['.', ''], $this->name);
    }
}
