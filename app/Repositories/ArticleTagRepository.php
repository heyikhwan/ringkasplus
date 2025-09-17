<?php

namespace App\Repositories;

use App\Models\ArticleTag;
use App\Repositories\BaseRepositories;

class ArticleTagRepository extends BaseRepositories
{
    protected ArticleTag $model;

    public function __construct(ArticleTag $model)
    {
        $this->model = $model;
    }
}
