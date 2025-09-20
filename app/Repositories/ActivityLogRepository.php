<?php

namespace App\Repositories;

use Spatie\Activitylog\Models\Activity;

class ActivityLogRepository extends BaseRepositories
{
    protected Activity $model;

    public function __construct(Activity $model)
    {
        $this->model = $model;
    }

    public function getByBatchUuid($uuid, $exclude_id = null)
    {
        if (empty($uuid)) {
            return collect([]);
        }

        return $this->getBaseQuery()
            ->where('batch_uuid', $uuid)
            ->when(!empty($exclude_id), function ($q) use ($exclude_id) {
                $q->where('id', '!=', $exclude_id);
            })
            ->orderBy('id', 'desc')
            ->get();
    }
}
