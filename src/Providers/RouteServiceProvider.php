<?php

namespace Azuriom\Plugin\ReachUs\Providers;

use Azuriom\Extensions\Plugin\BaseRouteServiceProvider;
use Azuriom\Plugin\ReachUs\Middleware\LogReachUsRequest;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    /**
     * Define the routes for the application.
     */
    public function loadRoutes(): void
    {
        Route::middleware(['web', LogReachUsRequest::class])
            ->prefix($this->plugin->id)
            ->name($this->plugin->id.'.')
            ->group(plugin_path($this->plugin->id.'/routes/web.php'));

        Route::middleware(['admin-access', LogReachUsRequest::class])
            ->prefix('admin/'.$this->plugin->id)
            ->name($this->plugin->id.'.admin.')
            ->group(plugin_path($this->plugin->id.'/routes/admin.php'));
    }
}
