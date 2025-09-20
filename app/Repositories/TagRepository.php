<?php

namespace App\Repositories;

use App\Models\Tag;
use App\Repositories\BaseRepositories;

class TagRepository extends BaseRepositories
{
    protected Tag $model;

    public function __construct(Tag $model)
    {
        $this->model = $model;
    }
}
