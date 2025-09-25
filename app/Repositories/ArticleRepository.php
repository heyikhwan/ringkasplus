<?php

namespace App\Repositories;

use App\Models\Article;
use App\Repositories\BaseRepositories;

class ArticleRepository extends BaseRepositories
{
    protected Article $model;

    public function __construct(Article $model)
    {
        $this->model = $model;
    }

    public function getStatusOptions()
    {
        return $this->model->statusOptions();
    }
}
