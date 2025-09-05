<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exceptions\AppException;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuthService
{
    public function login(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $username = $request->input('username');
        $password = $request->input('password');

        if (Auth::attempt(['username' => $username, 'password' => $password])) {
            $user = Auth::user();

            activity('auth')->causedBy($user)->event('login')->log('Login App');

            RateLimiter::clear($this->throttleKey($request));
        }

        RateLimiter::hit($this->throttleKey($request));
        throw new AppException('Username atau password salah');
    }

    public function ensureIsNotRateLimited(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw new AppException('Terlalu banyak percobaan login. Silahkan coba lagi dalam ' . $seconds . ' detik.');
    }

    public function throttleKey(Request $request)
    {
        return Str::lower($request->input('username')) . '|' . $request->ip();
    }

    public function logout()
    {
        activity('auth')->causedBy(Auth::id())->event('logout')->log('Logout App');
        Auth::logout();
    }
}
