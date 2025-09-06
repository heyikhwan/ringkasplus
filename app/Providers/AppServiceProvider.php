<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability, $arguments) {
            if (! $user->hasRole('Super Admin')) {
                return null;
            }

            if (
                in_array($ability, ['update', 'delete'], true)
                && isset($arguments[0])
                && $arguments[0] instanceof Role
            ) {
                return null;
            }

            if (
                in_array($ability, ['update', 'delete', 'resetPassword'], true)
                && isset($arguments[0])
                && $arguments[0] instanceof User
            ) {
                return null;
            }

            return true;
        });
    }
}
