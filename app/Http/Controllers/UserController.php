<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Http\Requests\UserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    // TODO: Assign role to user dan tampilkan role di index
    protected $title = 'Pengguna';
    protected $view = 'app.user';
    protected $permission_name = 'user';

    public $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

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
