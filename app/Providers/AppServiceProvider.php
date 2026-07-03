<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;

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
        Paginator::useBootstrap();

        RedirectIfAuthenticated::redirectUsing(function ($request) {
            $user = $request->user();
            if ($user) {
                if ($user->role === 'admin') {
                    return route('admin.dashboard');
                } elseif ($user->role === 'sales') {
                    return route('sales.dashboard');
                }
            }
            return '/';
        });
    }
}
