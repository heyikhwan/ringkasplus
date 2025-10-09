<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ActivityLogController extends Controller implements HasMiddleware
{
    protected $title = 'Log Aktivitas';
    protected $view = 'app.activity-log';
    protected $permission_name = 'activity-log';

    public $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;

        $this->setupConstruct();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:activity-log.index', only: ['index']),
            new Middleware('can:activity-log.show', only: ['show']),
        ];
    }

    public function index()
    {
        $this->setBreadcrumbs([
            [
                'title' => $this->title
            ]
        ]);

        return view("{$this->view}.index");
    }

    public function datatable()
    {
        return $this->activityLogService->datatable($this->permission_name);
    }

    public function show($id)
    {
        $this->setBreadcrumbs([
            [
                'title' => $this->title,
                'url' => route("{$this->permission_name}.index")
            ],
            [
                'title' => 'Detail'
            ]
        ]);

        $result = $this->activityLogService->findById(decode($id));
        $detail = $this->activityLogService->getByBatchUuid($result->batch_uuid, $result->id);

        return view("{$this->view}.show", [
            'result' => $result,
            'detail' => $detail
        ]);
    }
}
