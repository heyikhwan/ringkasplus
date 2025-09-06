<?php

namespace App\Repositories;

use App\Models\Permission;
use App\Repositories\BaseRepositories;
use Illuminate\Support\Facades\DB;

class PermissionRepository extends BaseRepositories
{
    protected Permission $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function updateStatusAndCleanRoles(array $activePermissionIds)
    {
        DB::transaction(function () use ($activePermissionIds) {

            // Update permission yang aktif
            $this->model->whereIn('id', $activePermissionIds)->update(['status' => 1]);

            // Update permission yang tidak aktif
            $this->model->whereNotIn('id', $activePermissionIds)->update(['status' => 0]);

            // Hapus relasi role untuk permission yang nonaktif
            $inactivePermissions = $this->model
                ->whereNotIn('id', $activePermissionIds)
                ->pluck('id');

            if ($inactivePermissions->isNotEmpty()) {
                DB::table('role_has_permissions')
                    ->whereIn('permission_id', $inactivePermissions)
                    ->delete();
            }
        });
    }
}
