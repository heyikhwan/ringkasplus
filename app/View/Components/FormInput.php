<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormInput extends Component
{
    public $label;
    public $type;
    public $id;
    public $name;
    public $autocomplete;
    public $required;
    public $value;
    public $placeholder;
    public $help;
    public $class;

    public function __construct($label = null, $type = 'text', $id = null, $name = null, $autocomplete = 'off', $required = false, $value = null, $placeholder = null, $help = null, $class = null)
    {
        if (!$id && $name) {
            $id = $name;
        } elseif ($id && !$name) {
            $name = $id;
        }

        $this->label = $label;
        $this->type = $type;
        $this->id = $id;
        $this->name = $name;
        $this->autocomplete = $autocomplete;
        $this->required = $required;
        $this->value = $value;
        $this->placeholder = $placeholder ?? '';
        $this->help = $help;
        $this->class = $class ?? '';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-input');
    }

    public function errorKey()
    {
        return str_replace(['[', ']'], ['.', ''], $this->name);
    }
}
