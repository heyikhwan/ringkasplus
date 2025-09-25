<?php

namespace App\Http\Controllers;

use App\Repositories\CategoryRepository;
use App\Repositories\RoleRepository;
use App\Repositories\TagRepository;

class Select2Controller extends Controller
{
    protected $roleRepository;
    protected $categoryRepository;
    protected $tagRepository;

    public function __construct(
        RoleRepository $roleRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository
    ) {
        $this->roleRepository = $roleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
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

    public function categories()
    {
        $search = ['name'];
        $select = ['id', 'slug', 'name'];

        return $this->categoryRepository->getAll(
            callback: function ($query) use ($search, $select) {

                $query->where(function ($q) use ($search) {
                    foreach ($search as $column) {
                        $q->orWhere($column, 'LIKE', '%' . request()->q . '%');
                    }
                })
                    ->where('status', true)
                    ->orderBy('name', 'asc')
                    ->select($select);

                return $query;
            }
        );
    }

    public function tags()
    {
        $search = ['name'];
        $select = ['id', 'slug', 'name'];

        return $this->tagRepository->getAll(
            callback: function ($query) use ($search, $select) {

                $query->where(function ($q) use ($search) {
                    foreach ($search as $column) {
                        $q->orWhere($column, 'LIKE', '%' . request()->q . '%');
                    }
                })
                    ->where('status', true)
                    ->orderBy('name', 'asc')
                    ->select($select);

                return $query;
            }
        );
    }
}
