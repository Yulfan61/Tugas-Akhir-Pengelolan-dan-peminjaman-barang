<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Observers\UserObserver;
use App\Models\Blog;
use App\Policies\BlogPolicy;

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
    public function boot()
    {
        if (config('app.env') === 'production' || request()->header('x-forwarded-proto') === 'https') {
            \URL::forceScheme('https');
        }

        // ✅ Share notifikasi ke semua view
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('notifications', Auth::user()->notifications()->latest()->take(10)->get());
            }
        });


    }
}
