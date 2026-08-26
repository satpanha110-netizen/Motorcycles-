<?php

namespace App\Providers;

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
        // Make StorageHelper available in Blade views without a namespace.
        class_alias(\App\Models\StorageHelper::class, 'StorageHelper');

        // Render pagination links with Bootstrap 5 markup.
        \Illuminate\Pagination\Paginator::useBootstrapFive();
    }
}
