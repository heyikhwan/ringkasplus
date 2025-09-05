<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormImage extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $default;
    public $background;
    public $name;
    public $nameRemove;
    public $accept;
    public $width;
    public $height;
    public $action;
    public $onlyShow;

    public function __construct(
        $name,
        $default = NULL,
        $background = '/app/assets/media/avatars/blank.png',
        $nameRemove = NULL,
        $accept = '.png, .jpg, .jpeg, .svg, .webp',
        $width = "125px",
        $height = "125px",
        $action = false,
        $onlyShow = false,

    ) {
        $this->name = $name;
        $this->default = empty($default) ? NULL : $default;
        $this->background = $background;
        $this->nameRemove = $nameRemove ?: $name . '_remove';
        $this->accept = $accept;
        $this->width = $width;
        $this->height = $height;
        $this->action = empty($action) ? false : $action;
        $this->onlyShow = $onlyShow;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-image');
    }
}
