<?php

namespace App\Repositories;

use App\Models\ArticleCategory;
use App\Repositories\BaseRepositories;

class ArticleCategoryRepository extends BaseRepositories
{
    protected ArticleCategory $model;

    public function __construct(ArticleCategory $model)
    {
        $this->model = $model;
    }
}
