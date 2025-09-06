<?php

namespace App\Services;

use App\Repositories\PermissionGroupRepository;
use App\Repositories\PermissionRepository;

class PermissionService
{
    protected $permissionGroupRepository;

    public function __construct(PermissionGroupRepository $permissionGroupRepository)
    {
        $this->permissionGroupRepository = $permissionGroupRepository;
    }

    public function getAllGroup($status = null)
    {
        return $this->permissionGroupRepository->getAll(
            ['permissions'],
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
}
