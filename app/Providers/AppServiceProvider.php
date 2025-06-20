<?php
// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

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
        // Set default string length for older MySQL versions
        Schema::defaultStringLength(191);

        // Set Carbon timezone and locale
        Carbon::setLocale('id');

        // Set timezone for the entire application
        date_default_timezone_set('Asia/Jakarta');

        // Set Carbon timezone
        Carbon::now()->setTimezone('Asia/Jakarta');
    }
}
