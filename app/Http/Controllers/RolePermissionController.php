<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Http\Requests\RoleRequest;
use App\Services\PermissionService;
use App\Services\RoleService;

class RolePermissionController extends Controller
{
    // TODO: Feat. Hak Akses Aplikasi
    protected $title = 'Peran & Hak Akses Pengguna';
    protected $view = 'app.role-permission';
    protected $permission_name = 'role-permission';

    protected $roleService, $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;

        $this->setupConstruct();
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
        return $this->roleService->datatable($this->permission_name);
    }

    public function create()
    {
        $this->setBreadcrumbs([
            [
                'title' => $this->title,
                'url' => route("$this->permission_name.index")
            ],
            [
                'title' => 'Tambah Data'
            ]
        ]);

        $permissions = $this->permissionService->getAllGroup(1);

        return view("{$this->view}.create", [
            'permissions' => $permissions
        ]);
    }

    public function store(RoleRequest $request)
    {
        $data = $request->validated();

        try {
            $this->roleService->create($data);

            return redirect()->route("$this->permission_name.index")->with('success', BERHASIL_SIMPAN);
        } catch (AppException $e) {
            return responseFail($e->getMessage());
        } catch (\Throwable $th) {
            return responseFail(GAGAL_SIMPAN);
        }
    }

    public function edit($id)
    {
        $this->setBreadcrumbs([
            [
                'title' => $this->title,
                'url' => route("$this->permission_name.index")
            ],
            [
                'title' => 'Ubah Data'
            ]
        ]);

        $result = $this->roleService->findById(decode($id));
        $this->authorize('update', $result);

        $permissions = $this->permissionService->getAllGroup(1);

        $selectedPermissions = $result->permissions->pluck('id')->toArray();

        return view("{$this->view}.edit", [
            'result' => $result,
            'permissions' => $permissions,
            'selectedPermissions' => $selectedPermissions
        ]);
    }

    public function update(RoleRequest $request, $id)
    {
        $role = $this->roleService->findById(decode($id));
        $this->authorize('update', $role);

        $data = $request->validated();

        try {
            $this->roleService->update(decode($id), $data);

            return redirect()->route("$this->permission_name.index")->with('success', BERHASIL_UPDATE);
        } catch (AppException $e) {
            return responseFail($e->getMessage());
        } catch (\Throwable $th) {
            return responseFail(GAGAL_UPDATE);
        }
    }

    public function destroy($id)
    {
        notAjaxAbort();

        $role = $this->roleService->findById(decode($id));
        $this->authorize('delete', $role);

        try {
            $this->roleService->destroy(decode($id));

            return responseSuccess(BERHASIL_HAPUS);
        } catch (AppException $e) {
            return responseFail($e->getMessage());
        } catch (\Throwable $th) {
            return responseFail(GAGAL_HAPUS);
        }
    }
}
