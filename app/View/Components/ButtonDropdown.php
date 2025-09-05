<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ButtonDropdown extends Component
{
    public $items;
    public $label;
    public $icon;
    public $class;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $items = [],
        $label = '',
        $icon = '',
        $class = ''
    ) {
        $this->items = $items;
        $this->label = $label;
        $this->icon = $icon;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button-dropdown');
    }
}
