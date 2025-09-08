<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index()
    {
        return view('app.auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50',
            'captcha' => 'required|captcha',
        ], [
            'captcha.required' => ':attribute harus diisi',
            'captcha.captcha' => ':attribute salah',
        ], [
            'captcha' => 'kode captcha',
        ]);

        try {
            $result = $this->authService->forgotPassword($request);
            $email = maskEmail($result->email);

            return redirect()->back()->with('success', "Link reset password telah dikirim ke email $email");
        } catch (AppException $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (Throwable $e) {
            return redirect()->back()->with('error', TERJADI_KESALAHAN)->withInput();
        }
    }
}
