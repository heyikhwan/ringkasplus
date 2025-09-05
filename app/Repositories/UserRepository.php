<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepositories
{
    protected User $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }
}
