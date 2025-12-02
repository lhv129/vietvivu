<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->mapApiRoutesByVersion('v1');
        $this->mapApiRoutesByVersion('v2');
    }

    protected function mapApiRoutesByVersion($version)
    {
        $directory = base_path("app/Api/Routes/{$version}");

        foreach (glob($directory . '/*.php') as $routeFile) {
            Route::prefix("api/{$version}")
                ->middleware('api')
                ->group($routeFile);
        }
    }
}
