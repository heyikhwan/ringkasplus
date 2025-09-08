<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    protected $title = 'Pengaturan Akun';
    protected $view = 'app.account';
    protected $permission_name = 'account';

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
                'title' => $this->title,
            ]
        ]);

        return view("{$this->view}.index");
    }

    public function edit()
    {
        $this->setBreadcrumbs([
            [
                'title' => $this->title,
                'url' => route("$this->permission_name.index")
            ],
            [
                'title' => 'Ubah Data',
            ]
        ]);

        return view("{$this->view}.edit");
    }

    public function update(UserRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $this->userService->update(auth()->user()->id, $data);

            return redirect()->route("$this->permission_name.index")->with('success', BERHASIL_UPDATE);
        } catch (AppException $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', GAGAL_UPDATE)->withInput();
        }
    }

    public function changePassword(Request $request)
    {
        if ($request->isMethod('post')) {
            notAjaxAbort();

            $validator = \Validator::make($request->all(), [
                'old_password' => 'required',
                'password' => 'required|min:8|confirmed',
            ]);

            if (!Hash::check($request->old_password, auth()->user()->password)) {
                $validator->errors()->add('old_password', 'password lama tidak sesuai.');
            }

            if ($validator->errors()->count() > 0) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            try {
                $this->userService->updatePassword(auth()->user()->id, $request->only('password'));

                return responseSuccess('Password berhasil diperbarui');
            } catch (\Throwable $e) {
                return responseFail('Gagal perbarui password');
            }
        }


        return view("{$this->view}.change-password");
    }

    public function sendVerifyEmail()
    {
        try {
            $this->userService->sendVerifyEmail(auth()->user()->id);

            return responseSuccess('Email verifikasi berhasil dikirim. Silahkan cek email Anda.');
        } catch (\Throwable $e) {
            return responseFail(TERJADI_KESALAHAN);
        }
    }

    public function verifyEmail($id, $hash)
    {
        try {
            $this->userService->verifyEmail(decode($id), $hash);

            return redirect()->route("$this->permission_name.index")->with('success', 'Email berhasil diverifikasi.');
        } catch (AppException $e) {
            return redirect()->route("$this->permission_name.index")->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return redirect()->route("$this->permission_name.index")->with('error', TERJADI_KESALAHAN);
        }
    }
}
