<?php

namespace App\Providers;

use Botble\Base\Facades\DashboardMenu;
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
        DashboardMenu::removeItem('cms-core-plugins');
    }
}
