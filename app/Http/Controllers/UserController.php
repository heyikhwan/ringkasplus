<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Http\Requests\UserRequest;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    protected $title = 'Pengguna';
    protected $view = 'app.user';
    protected $permission_name = 'user';

    public $userService;
    public $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;

        $this->setupConstruct();
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:user.index', only: ['index']),
            new Middleware('can:user.create', only: ['create', 'store']),
            new Middleware('can:user.edit', only: ['edit', 'update']),
            new Middleware('can:user.destroy', only: ['destroy']),
            new Middleware('can:user.resetPassword', only: ['resetPassword']),
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
        return $this->userService->datatable($this->permission_name);
    }

    public function create()
    {
        return view("{$this->view}.create");
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();

        try {
            $this->userService->create($data);

            return responseSuccess(BERHASIL_SIMPAN);
        } catch (AppException $e) {
            return responseFail($e->getMessage());
        } catch (\Throwable $th) {
            return responseFail(GAGAL_SIMPAN);
        }
    }

    public function edit($id)
    {
        notAjaxAbort();

        $result = $this->userService->findById(decode($id));

        $roleIds = $result->roles->pluck('id')->toArray();
        $result->roles = $this->roleService->getAll(limit: 0, paginate: false, callback: function ($q) use ($roleIds) {
            $q->whereIn('id', $roleIds);
        })->pluck('name', 'id');

        $this->authorize('update', $result);


        return view("{$this->view}.edit", [
            'result' => $result
        ]);
    }

    public function update(UserRequest $request, $id)
    {
        $user = $this->userService->findById(decode($id));
        $this->authorize('update', $user);

        $data = $request->validated();

        try {
            $this->userService->update(decode($id), $data);

            return responseSuccess(BERHASIL_UPDATE);
        } catch (AppException $e) {
            return responseFail($e->getMessage());
        } catch (\Throwable $th) {
            return responseFail(GAGAL_UPDATE);
        }
    }

    public function destroy($id)
    {
        notAjaxAbort();

        $user = $this->userService->findById(decode($id));
        $this->authorize('delete', $user);

        try {
            $this->userService->destroy(decode($id));

            return responseSuccess(BERHASIL_HAPUS);
        } catch (AppException $e) {
            return responseFail($e->getMessage());
        } catch (\Throwable $th) {
            return responseFail(GAGAL_HAPUS);
        }
    }

    public function resetPassword($id)
    {
        notAjaxAbort();

        $user = $this->userService->findById(decode($id));
        $this->authorize('resetPassword', $user);

        try {
            $this->userService->resetPassword(decode($id));

            return responseSuccess("Password berhasil direset");
        } catch (AppException $e) {
            return responseFail($e->getMessage());
        } catch (\Throwable $th) {
            return responseFail("Gagal reset password");
        }
    }
}
