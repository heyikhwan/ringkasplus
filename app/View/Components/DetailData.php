<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DetailData extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $details;
    public $vertical;
    public function __construct($details, $vertical = false)
    {
        $this->details = $details;
        $this->vertical = $vertical; // jika vertical, maka data dengan key yang sama akan sejajar secara vertical (di mobile)
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.detail-data');
    }
}
