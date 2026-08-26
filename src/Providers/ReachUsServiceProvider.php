<?php

namespace Azuriom\Plugin\ReachUs\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Plugin\ReachUs\Services\ReachUsSettings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ReachUsServiceProvider extends BasePluginServiceProvider
{
    /**
     * Bootstrap the plugin services.
     */
    public function boot(): void
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadMigrations();
        $this->registerRouteDescriptions();
        $this->registerAdminNavigation();
        $this->registerRateLimiter();
    }

    /**
     * Return the routes that can be added to the site navigation.
     *
     * @return array<string, string>
     */
    protected function routeDescriptions(): array
    {
        return [
            'reachus.index' => trans('reachus::messages.title'),
        ];
    }

    /**
     * Return the plugin entries for the administration navigation.
     *
     * @return array<string, array<string, string>>
     */
    protected function adminNavigation(): array
    {
        return [];
    }

    protected function registerRateLimiter(): void
    {
        RateLimiter::for('reachus.contact', function (Request $request) {
            $attempts = $this->app->make(ReachUsSettings::class)->rateLimit();

            return Limit::perMinute($attempts)
                ->by('reachus.contact|'.$request->ip())
                ->response(fn (Request $request, array $headers) => to_route('reachus.index')
                    ->withErrors(['form' => trans('reachus::messages.form.too_many_attempts')])
                    ->withInput($request->except('contact_value'))
                    ->withHeaders($headers));
        });
    }
}
