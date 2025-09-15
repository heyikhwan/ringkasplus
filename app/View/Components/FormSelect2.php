<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormSelect2 extends Component
{
    public $label;
    public $placeholder;
    public $url;
    public $id;
    public $name;
    public $class;
    public $disableSearch;
    public $allowClear;
    public $tags;
    public $closeOnSelect;
    public $disabled;
    public $required;
    public $noScript;
    public $options;

    public function __construct(
        $url = null,
        $label = null,
        $placeholder = null,
        $id = null,
        $name = null,
        $class = null,
        $disableSearch = false,
        $allowClear = false,
        $tags = false,
        $closeOnSelect = true,
        $disabled = false,
        $required = false,
        $noScript = false,
        $options = []
    ) {
        if (!$id && $name) {
            $id = $name;
        } elseif ($id && !$name) {
            $name = $id;
        }

        $this->id = $id;
        $this->name = $name;
        $this->class = $class;
        $this->label = $label;
        $this->disabled = $disabled;
        $this->required = $required;
        $this->noScript = $noScript;

        $this->placeholder = $placeholder ?? (!empty($label) ? 'Pilih ' . strip_tags($label) : 'Pilih');

        $this->options = array_merge([
            'url'           => $url,
            'placeholder'   => $this->placeholder,
            'disableSearch' => $disableSearch,
            'allowClear'    => $allowClear,
            'tags'          => $tags,
            'closeOnSelect' => $closeOnSelect,
        ], $options);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form-select2');
    }
}
