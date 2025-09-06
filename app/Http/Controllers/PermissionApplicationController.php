<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class PermissionApplicationController extends Controller
{
    protected $title = 'Hak Akses Aplikasi';
    protected $view = 'app.permission-application';
    protected $permission_name = 'permission-application';

    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;

        $this->setupConstruct();
    }

    public function create()
    {
        $this->setBreadcrumbs([
            [
                'title' => $this->title,
            ]
        ]);

        $permissions = $this->permissionService->getAllGroup();

        return view("{$this->view}.create", [
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        notAjaxAbort();
        $data = $request->except(['_token']);

        try {
            $this->permissionService->saveStatusPermission($data);

            return responseSuccess(BERHASIL_SIMPAN);
        } catch (AppException $e) {
            return responseFail($e->getMessage());
        } catch (\Throwable $th) {
            return responseFail(GAGAL_SIMPAN);
        }
    }
}
