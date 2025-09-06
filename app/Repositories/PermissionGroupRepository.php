<?php

namespace App\Repositories;

use App\Models\PermissionGroup;
use App\Repositories\BaseRepositories;

class PermissionGroupRepository extends BaseRepositories
{
    protected PermissionGroup $model;

    public function __construct(PermissionGroup $model)
    {
        $this->model = $model;
    }
}
