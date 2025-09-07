<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormSwitch extends Component
{
    public $label;
    public $labelOn;
    public $name;
    public $id;
    public $checked;
    public $required;
    public $help;

    public function __construct($label = null, $labelOn = null, $name = null, $id = null, $checked = false, $required = false, $help = null)
    {
        if (!$id && $name) {
            $id = $name;
        } elseif ($id && !$name) {
            $name = $id;
        }

        $this->label = $label;
        $this->labelOn = $labelOn;
        $this->name = $name;
        $this->id = $id;
        $this->checked = $checked;
        $this->required = $required;
        $this->help = $help;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-switch');
    }

    public function errorKey()
    {
        return str_replace(['[', ']'], ['.', ''], $this->name);
    }
}
