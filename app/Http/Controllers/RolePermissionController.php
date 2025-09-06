<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Http\Requests\RoleRequest;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RolePermissionController extends Controller implements HasMiddleware
{
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

    public static function middleware(): array
    {
        return [
            new Middleware('can:role-permission.index', only: ['index']),
            new Middleware('can:role-permission.create', only: ['create', 'store']),
            new Middleware('can:role-permission.edit', only: ['edit', 'update']),
            new Middleware('can:role-permission.destroy', only: ['destroy']),
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
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', GAGAL_SIMPAN)->withInput();
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

        $isDefaultRole = $this->roleService->isDefaultRole($result->name);

        $permissions = $this->permissionService->getAllGroup(1);
        $selectedPermissions = $result->permissions->pluck('id')->toArray();

        return view("{$this->view}.edit", [
            'result' => $result,
            'isDefaultRole' => $isDefaultRole,
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
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', GAGAL_UPDATE)->withInput();
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
