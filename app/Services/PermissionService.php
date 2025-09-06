<?php

namespace App\Services;

use App\Repositories\PermissionGroupRepository;
use App\Repositories\PermissionRepository;
use Illuminate\Support\Facades\DB;

class PermissionService
{
    protected $permissionGroupRepository, $permissionRepository;

    public function __construct(PermissionGroupRepository $permissionGroupRepository, PermissionRepository $permissionRepository)
    {
        $this->permissionGroupRepository = $permissionGroupRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function getAllGroup($status = null)
    {
        return $this->permissionGroupRepository->getAll(
            ['permissions' => function ($query) use ($status) {
                $query->when(!is_null($status), function ($query) use ($status) {
                    $query->where('status', $status);
                });
            }],
            0,
            false,
            function ($query) use ($status) {
                $query->whereHas('permissions', function ($query) use ($status) {
                    $query->when(!is_null($status), function ($query) use ($status) {
                        $query->where('status', $status);
                    });
                });
            }
        );
    }

    public function saveStatusPermission($request)
    {
        DB::beginTransaction();

        try {
            $activePermissionIds = array_keys($request['permissions'] ?? []);
            $result = $this->permissionRepository->updateStatusAndCleanRoles($activePermissionIds);

            DB::commit();
            return $result;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
