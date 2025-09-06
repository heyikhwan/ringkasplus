<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\BaseRepositories;

class PermissionRepository extends BaseRepositories
{
    protected Permission $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }
}
