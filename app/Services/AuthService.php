<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exceptions\AppException;
use App\Mail\ForgotPasswordMail;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $username = $request->input('username');
        $password = $request->input('password');

        $user = $this->userRepository->findByUsername($username);
        if ($user && Hash::check($password, $user->password) && !$user->is_active) {
            throw new AppException('Akun anda sudah dinonaktifkan. Silahkan hubungi Admin');
        }

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

    public function forgotPassword($request)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->findByUsername($request->username);
            if (!$user) {
                throw new AppException('Username tidak ditemukan');
            }

            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => $token, 'created_at' => now()]
            );

            $url = URL::temporarySignedRoute(
                'auth.reset-password.index',
                now()->addMinutes(60),
                ['id' => encode($user->id), 'token' => $token]
            );

            $format_email = defaultFormatBodyEmail('forgot_password_mail');

            $format_email = str_replace([
                '{name}',
                '{link_btn_action}',
                '{expired}'
            ], [
                $user->name,
                $url,
                60
            ], $format_email);

            $data = [
                'title' => 'Permintaan Reset Password',
                'subject' => 'Permintaan Reset Password',
                'body' => $format_email,
            ];

            Mail::to($user->email)->send(new ForgotPasswordMail($data));

            DB::commit();

            activity('auth')->causedBy($user)->event('forgot password')->log('Lupa password');

            return $user;
        } catch (AppException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function resetPassword($request)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->findById(decode($request['user_id']));
            if (!$user) {
                throw new AppException('Username tidak ditemukan');
            }

            $token = DB::table('password_reset_tokens')
                ->where('token', $request['token'])
                ->first();
            if (!$token) {
                throw new AppException('Token tidak valid');
            }

            $user->password = Hash::make($request['password']);
            $user->save();

            DB::table('password_reset_tokens')->where('token', $request['token'])->delete();

            DB::commit();

            activity('auth')->causedBy($user)->event('reset password')->log('Reset password');

            return $user;
        } catch (AppException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
