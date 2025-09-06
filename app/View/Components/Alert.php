<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $alertType;
    public $title;
    public $icon;
    public $dismiss;
    public function __construct($alertType, $title = null, $dismiss = false)
    {
        $this->alertType = $alertType;
        $this->title = $title;
        $this->dismiss = $dismiss == 'false' ? false : $dismiss;

        switch ($alertType) {
            case 'success':
                $icon = 'fa-solid fa-circle-check';
                break;
            case 'warning':
                $icon = 'fa-solid fa-triangle-exclamation';
                break;
            case 'danger':
                $icon = 'fa-solid fa-circle-exclamation';
                break;
            default:
                $icon = 'fa-solid fa-circle-info';
                break;
        }

        $this->icon = $icon;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.alert');
    }
}
