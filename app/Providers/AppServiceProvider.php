<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use App\Models\Spring;
use App\Modules\NajmBahar\Adapters\LegacyNajmAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register Blade namespace for module views
        View::addNamespace('Stock', base_path('app/Modules/Stock/Views'));
        View::addNamespace('Blog', base_path('app/Modules/Blog/Views'));

        // Blade directive برای چک کردن دسترسی
        Blade::if('hasPermission', function ($permission) {
            $user = Auth::user();
            return $user && $user->hasPermission($permission);
        });

        // Blade directive برای چک کردن نقش
        Blade::if('hasRole', function ($role) {
            $user = Auth::user();
            return $user && $user->hasRole($role);
        });

        // Blade directive برای چک کردن Super Admin
        Blade::if('isSuperAdmin', function () {
            $user = Auth::user();
            return $user && ($user->is_admin || $user->hasRole('super-admin'));
        });

        // register listener to mirror legacy Spring creations into NajmBahar module
        Spring::created(function ($spring) {
            // dispatch a queued job to handle mirroring (non-blocking)
            try {
                \App\Jobs\ProcessSpringCreatedNajm::dispatch($spring->id);
            } catch (\Throwable $e) {
                // fallback to synchronous adapter call if dispatch fails
                try {
                    LegacyNajmAdapter::onSpringCreated($spring);
                } catch (\Throwable $ex) {
                    // log and continue
                    \Illuminate\Support\Facades\Log::error('Najm adapter fallback failed: ' . $ex->getMessage());
                }
            }
        });
    }
}
