<?php

namespace App\Services;

use App\Exceptions\AppException;
use App\Repositories\RoleRepository;
use App\Traits\ActivityLogUser;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RoleService
{
    use ActivityLogUser;

    protected $logName = 'Peran & Hak Akses';

    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function datatable($permission_name)
    {
        $query = $this->roleRepository->getBaseQuery();

        if (!auth()->user()->hasRole('Super Admin')) {
            $query->where('name', '!=', 'Super Admin');
        }

        return DataTables::eloquent($query)
            ->addColumn('action', function ($row) use ($permission_name) {
                $primaryKey = encrypt($row->id);

                $items = [];

                $authUser = auth()->user();

                if ($authUser->can('update', $row)) {
                    $items[] = [
                        'permission' => "$permission_name.edit",
                        'title'      => 'Ubah',
                        'icon'       => '<i class="ki-duotone ki-pencil fs-2"><span class="path1"></span><span class="path2"></span></i>',
                        'url'        => route("$permission_name.edit", $primaryKey),
                    ];
                }

                if ($authUser->can('delete', $row)) {
                    $items[] =
                        [
                            'permission' => "$permission_name.destroy",
                            'title' => 'Hapus',
                            'icon' => '<i class="ki-duotone ki-trash fs-2 text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>',
                            'class' => 'text-danger',
                            'attributes' => [
                                'onclick' => "forceDeleteDataDataTable('" . route("$permission_name.destroy", $primaryKey) . "')",
                            ]
                        ];
                }

                return view('components.button-dropdown', [
                    'items' => $items
                ])->render();
            })
            ->addColumn('permissions', function ($row) {
                return $row->permissions()->count();
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }

    public function findById($id, $with = [])
    {
        return $this->roleRepository->findById($id, $with);
    }

    public function isDefaultRole($role)
    {
        return $this->roleRepository->isDefaultRole($role);
    }

    public function create($request)
    {
        DB::beginTransaction();

        try {
            // create role
            $role = $this->roleRepository->create([
                'name' => $request['name'],
            ]);

            // create role permissions
            $permissions = !empty($request['permissions']) ? array_keys($request['permissions']) : [];
            if (!empty($permissions)) {
                $role->permissions()->sync($permissions);
            }

            DB::commit();

            $this->activityCreate('Menambahkan peran baru', $role);

            return $role;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($id, $request)
    {
        $role = $this->findById($id);
        if (!$role) {
            throw new AppException(DATA_TIDAK_DITEMUKAN);
        }

        DB::beginTransaction();

        try {
            // update role
            if (isset($request['name'])) {
                $role->update([
                    'name' => $request['name'],
                ]);
            }

            // update role permissions
            $permissions = !empty($request['permissions']) ? array_keys($request['permissions']) : [];
            if (!empty($permissions)) {
                $role->permissions()->sync($permissions);
            } else {
                $role->permissions()->detach();
            }

            DB::commit();

            $this->activityUpdate('Mengubah data peran dan hak akses', $role);

            return $role;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($id)
    {
        $role = $this->findById($id);
        if (!$role) {
            throw new AppException(DATA_TIDAK_DITEMUKAN);
        }

        try {
            $result = $this->roleRepository->delete($id);

            $this->activityDelete('Menghapus data peran', $role);

            return $result;
        } catch (\Throwable $e) {
            throw $e;
        }
    }
}
