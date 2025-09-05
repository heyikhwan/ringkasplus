<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableToolbar extends Component
{
    public $tableId;
    public $useSearch;
    public $useReset;
    public $useRefresh;
    public $class;

    public function __construct($tableId = DATATABLE_ID, $useSearch = true, $useReset = true, $useRefresh = true, $class = null)
    {
        $this->tableId = $tableId;
        $this->useSearch = $useSearch;
        $this->useReset = $useReset;
        $this->useRefresh = $useRefresh;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-toolbar');
    }
}
