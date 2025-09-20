<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\BaseRepositories;

class CategoryRepository extends BaseRepositories
{
    protected Category $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}
