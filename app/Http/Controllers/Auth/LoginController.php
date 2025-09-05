<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Throwable;

class LoginController extends Controller
{
    public $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function index()
    {
        return view('app.auth.login');
    }

    public function store(LoginRequest $request)
    {
        try {
            $this->authService->login($request);

            $request->session()->regenerate();

            return redirect()->route('dashboard');
        } catch (AppException $e) {
            return back()->with('error', $e->getMessage())->withInput();
        } catch (Throwable $e) {
            return back()->with('error', 'Terjadi kesalahan.')->withInput();
        }
    }
}
