<?php

namespace Azuriom\Plugin\ReachUs\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;

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
}
