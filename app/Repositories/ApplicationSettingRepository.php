<?php

namespace App\Repositories;

use App\Models\ApplicationSetting;
use App\Repositories\BaseRepositories;

class ApplicationSettingRepository extends BaseRepositories
{
    protected ApplicationSetting $model;

    protected $primaryKey = 'key';

    public function __construct(ApplicationSetting $model)
    {
        $this->model = $model;
    }

    public function findByKey($key)
    {
        return $this->model->where('key', $key)->first();
    }

    public function getByKey($key)
    {
        return $this->model->where('key', 'LIKE', '%' . $key . '%')->get();
    }

    public function updateOrCreate($key, $value)
    {
        return $this->model->updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
