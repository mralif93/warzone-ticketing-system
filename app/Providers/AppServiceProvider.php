<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        // Configure pagination to use WWC-branded Tailwind CSS
        Paginator::defaultView('vendor.pagination.wwc-tailwind');
        Paginator::defaultSimpleView('vendor.pagination.simple-tailwind');
    }
}
