<?php

namespace App\Repositories;

use App\Models\Role;
use App\Repositories\BaseRepositories;

class RoleRepository extends BaseRepositories
{
    protected Role $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function getDefaultRole()
    {
        return Role::defaultRoles();
    }
}
