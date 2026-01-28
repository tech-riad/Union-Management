<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register UnionHelper
        $this->app->singleton('union', function () {
            return new \App\Helpers\UnionHelper();
        });
    }

    public function boot(): void
    {
        // Share union settings with all views
        view()->composer('*', function ($view) {
            $view->with('unionSettings', \App\Helpers\UnionHelper::getSettings());
        });
    }
}