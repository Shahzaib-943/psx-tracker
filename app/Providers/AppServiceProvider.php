<?php

namespace App\Providers;

use App\Models\User;
use App\Constants\AppConstant;
use App\Models\FinanceCategory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
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
        Gate::before(function ($user, $ability) {
            return $user->hasRole(User::ROLE_ADMIN) ? true : null;
        });
    }
}
