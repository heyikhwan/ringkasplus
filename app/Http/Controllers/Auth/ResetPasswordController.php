<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    protected $authService;
    protected $userService;

    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    public function index(Request $request, $token)
    {
        $data = $request->all();

        $token_exists = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();
        if (!$token_exists) {
            return redirect()->route('auth.forgot-password')->with('error', 'Token tidak valid');
        }

        $user = $this->userService->findById(decode($data['id']));
        if (!$user) {
            abort(403, 'User tidak ditemukan');
        }

        return view('app.auth.reset-password', [
            'username' => $user->username,
            'user_id' => $data['id'],
            'token' => $token
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'captcha' => 'required|captcha'
        ], [
            'captcha.required' => ':attribute harus diisi',
            'captcha.captcha' => ':attribute salah',
        ], [
            'captcha' => 'kode captcha',
        ]);

        try {
            $this->authService->resetPassword($request->only(['user_id', 'password', 'token']));

            return redirect()->route('login')->with('success', "Password berhasil direset. Silahkan login kembali");
        } catch (AppException $e) {
            return redirect()->route('auth.forgot-password')->with('error', $e->getMessage())->withInput();
        } catch (Throwable $e) {
            return redirect()->back()->with('error', TERJADI_KESALAHAN)->withInput();
        }
    }
}
