<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $title;
    public $id;
    public $modalFooter;
    public $btnclose;
    public $btndone;
    public $msize;
    public function __construct(
        $title = NULL,
        $id = "kt_modal_1",
        $modalFooter = true,
        $btnclose = true,
        $btndone = true,
        $msize = '',
    ) {
        $this->title = $title;
        $this->id = $id;
        $this->modalFooter = $modalFooter;
        $this->btnclose = $btnclose;
        $this->btndone = $btndone;
        $this->msize = $msize;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
