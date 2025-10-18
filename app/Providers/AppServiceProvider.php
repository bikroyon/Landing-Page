<?php

namespace App\Providers;

use App\Services\MenuService;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

       Inertia::share([
        'auth' => fn () => ['user' => Auth::user()],
        'settings' => fn () => config('app.name'),

        'sidebar' => function () {
            $user = Auth::user();

            if (!$user) return []; // Prevent issues when user is not logged in

            return app(MenuService::class)->getMenusForRole($user->role_id);
        },
    ]);
    }
}