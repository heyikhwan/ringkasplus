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
    public $id;
    public $label;
    public $help;
    public $default;
    public $background;
    public $name;
    public $nameRemove;
    public $accept;
    public $width;
    public $height;
    public $action;
    public $onlyShow;
    public $required;
    public $removeUrl;

    public $hasImage;

    public function __construct(
        $name =  null,
        $default = NULL,
        $background = '/app/assets/media/avatars/blank.png',
        $nameRemove = NULL,
        $accept = '.png, .jpg, .jpeg, .svg, .webp',
        $width = "125px",
        $height = "125px",
        $action = false,
        $onlyShow = false,
        $id = null,
        $label = null,
        $help = null,
        $required = false,
        $removeUrl = null
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
        $this->id = $id ?: $name;
        $this->label = $label;
        $this->help = $help;
        $this->required = $required;
        $this->removeUrl = $removeUrl;

        $this->hasImage = !empty($background)
            && !str_contains($background, '/app/assets/media/avatars/blank.png')
            && !str_contains($background, '/app/assets/media/no-image.jpg');
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
