<?php

namespace App\Services;

use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\ActivityLogRepository;

class ActivityLogService
{
    protected $activityLogRepository;

    public function __construct(ActivityLogRepository $activityLogRepository)
    {
        $this->activityLogRepository = $activityLogRepository;
    }

    public function datatable($permission_name)
    {
        $subBatch = \DB::table('activity_log as t1')
            ->select('t1.id')
            ->whereNotNull('t1.batch_uuid')
            ->where('t1.batch_uuid', '!=', '')
            ->whereRaw('t1.id = (SELECT MAX(t2.id) FROM activity_log t2 WHERE t2.batch_uuid = t1.batch_uuid)');

        $query = $this->activityLogRepository
            ->getBaseQuery()
            ->join('users', 'users.id', '=', 'activity_log.causer_id')
            ->select(
                'activity_log.*',
                'users.name as user'
            )
            ->where(function ($q) use ($subBatch) {
                $q->whereNull('activity_log.batch_uuid')
                    ->orWhere('activity_log.batch_uuid', '=', '')
                    ->orWhereIn('activity_log.id', $subBatch);
            });

        if (request()->filled('tanggal')) {
            $query->whereDate('activity_log.created_at', request()->tanggal);
        }

        return DataTables::eloquent($query)
            ->addColumn('action', function ($row) use ($permission_name) {
                $primaryKey = encode($row->id);

                $authUser   = auth()->user();
                $items      = [];

                if ($authUser->can('show', $row)) {
                    $items[] = [
                        'permission' => "$permission_name.show",
                        'title' => 'Detail',
                        'icon' => '<i class="ki-duotone ki-search-list fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>',
                        'url' => route("$permission_name.show", $primaryKey),
                    ];
                }

                return view('components.button-dropdown', [
                    'items' => $items
                ])->render();
            })
            ->editColumn('created_at', fn($row) => tanggal($row->created_at, 'l, d F Y H:i:s') . ' WIB')
            ->editColumn('event', fn($row) => str_replace('_', ' ', Str::title($row->event)))
            ->filterColumn('user', fn($query, $keyword) => $query->where('users.name', 'like', "%{$keyword}%"))
            ->addIndexColumn()
            ->make(true);
    }

    public function findById($id, $with = [])
    {
        return $this->activityLogRepository->findById($id, $with);
    }

    public function getByBatchUuid($uuid, $exclude_id = null)
    {
        return $this->activityLogRepository->getByBatchUuid($uuid, $exclude_id);
    }
}
