<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Service\AuthService;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(Request $request)
    {
        $this->authService->logout();
        return redirect()->route('login');
    }
}
