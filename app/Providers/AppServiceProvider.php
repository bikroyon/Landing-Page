<?php

namespace App\Providers;

use App\Services\MenuService;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\StoreSetting;

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
        Inertia::share([
            'auth' => fn () => ['user' => Auth::user()],
            'settings' => function () {
                $store = StoreSetting::first();

                return [
                    'store_name' => $store->store_name ?? config('app.name'),
                    'store_tagline' => $store->store_tagline ?? null,
                    'favicon' => $store && $store->store_favicon
                        ? asset($store->store_favicon)
                        : asset('default-favicon.png'),
                    'logo' => $store && $store->store_logo
                        ? asset($store->store_logo)
                        : asset('default-logo.png'),
                    'meta_title' => $store->meta_title ?? null,
                    'meta_description' => $store->meta_description ?? null,
                ];
            },
            'sidebar' => function () {
                $user = Auth::user();

                if (!$user) return [];

                return app(MenuService::class)->getMenusForRole($user->role_id);
            },
        ]);
    }
}
