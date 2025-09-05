<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected $title = '';
    protected $breadcrumbs = [];
    protected $view = '';
    protected $permission_name = null;

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
