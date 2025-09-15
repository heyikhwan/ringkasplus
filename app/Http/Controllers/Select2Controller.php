<?php

namespace App\Http\Controllers;

use App\Repositories\RoleRepository;
use App\Services\RoleService;
use Illuminate\Http\Request;

class Select2Controller extends Controller
{
    protected $roleRepository;

    public function __construct(
        RoleRepository $roleRepository
    ) {
        $this->roleRepository = $roleRepository;
    }

    public function roles()
    {
        $search = ['name'];
        $select = ['id', 'name'];

        $user = auth()->user();

        return $this->roleRepository->getAll(
            callback: function ($query) use ($search, $select, $user) {

                $query->where(function ($q) use ($search) {
                    foreach ($search as $column) {
                        $q->orWhere($column, 'LIKE', '%' . request()->q . '%');
                    }
                });

                $query->select($select);

                if ($user) {
                    $rolesUser = $user->roles()->pluck('name')->toArray();

                    if (in_array('Super Admin', $rolesUser)) {
                        return $query;
                    } elseif (in_array('Admin', $rolesUser)) {
                        $query->where('name', '<>', 'Super Admin');
                    } else {
                        $query->whereNotIn('name', ['Super Admin', 'Admin']);
                    }
                } else {
                    $query->whereNotIn('name', ['Super Admin', 'Admin']);
                }

                return $query;
            }
        );
    }
}
