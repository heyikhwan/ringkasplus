<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormSelect extends Component
{
    public $label;
    public $id;
    public $name;
    public $required;
    public $defaultValue;
    public $options;
    public $placeholder;
    public $help;
    public $class;
    public $disableSearch;
    public $allowClear;
    public $closeOnSelect;
    public $tags;
    public $noScript;

    public function __construct($label = null, $id = null, $name = null, $required = false, $defaultValue = null, $placeholder = null, $help = null, $options = [], $class = null, $disableSearch = false, $allowClear = false, $closeOnSelect = true, $tags = false, $noScript = false)
    {
        if (!$id && $name) {
            $id = $name;
        } elseif ($id && !$name) {
            $name = $id;
        }

        $this->label = $label;
        $this->id = $id;
        $this->name = $name;
        $this->required = $required;
        $this->defaultValue = $defaultValue;
        $this->options = $options;
        $this->placeholder = $placeholder;
        $this->help = $help;
        $this->class = $class;
        $this->disableSearch = $disableSearch;
        $this->allowClear = $allowClear;
        $this->closeOnSelect = $closeOnSelect;
        $this->tags = $tags;
        $this->noScript = $noScript;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-select');
    }
}
