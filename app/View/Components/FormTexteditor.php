<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormTexteditor extends Component
{
    public $label;
    public $id;
    public $name;
    public $required;
    public $value;
    public $placeholder;
    public $cols;
    public $rows;
    public $help;
    public $variant;

    public function __construct($label = null, $id = null, $name = null, $required = false, $value = null, $placeholder = null, $cols = null, $rows = 3, $help = null, $variant = 'basic')
    {
        $id = $id ?? $name;
        $name = $name ?? $id;

        $this->label = $label;
        $this->id = $id;
        $this->name = $name;
        $this->required = $required;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->cols = $cols;
        $this->rows = $rows;
        $this->help = $help;
        $this->variant = $variant;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-texteditor');
    }

    public function errorKey()
    {
        return str_replace(['[', ']'], ['.', ''], $this->name);
    }
}
