<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests;
    
    protected $title = '';
    protected $breadcrumbs = [];
    protected $view = '';
    protected $permission_name = null;
    protected $middleware = [];

    public function setupConstruct()
    {
        view()->share('permission_name', $this->permission_name);
        view()->share('resource_view', $this->view);
        view()->share('title', $this->title);
    }

    protected function setBreadcrumbs(array $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
        view()->share('breadcrumbs', $this->breadcrumbs);
    }
}
