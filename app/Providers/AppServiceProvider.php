<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider as VendorTelescopeServiceProviderAlias;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->environment(['local'])) {
            $this->app->register(VendorTelescopeServiceProviderAlias::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
