<?php

namespace Azuriom\Plugin\ReachUs\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Permission;
use Azuriom\Models\Setting;
use Azuriom\Plugin\ReachUs\Services\ReachUsSettings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ReachUsServiceProvider extends BasePluginServiceProvider
{
    /**
     * Register sensitive settings before Azuriom hydrates them from storage.
     */
    public function register(): void
    {
        Setting::markAsEncrypted(ReachUsSettings::DISCORD_WEBHOOK_URL_KEY);
    }

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

        Permission::registerPermissions([
            'reachus.responses' => 'reachus::admin.permissions.responses',
            'reachus.settings' => 'reachus::admin.permissions.settings',
        ]);

        ActionLog::registerLogs('reachus.responses.deleted', [
            'icon' => 'trash',
            'color' => 'danger',
            'message' => 'reachus::admin.logs.response_deleted',
        ]);

        ActionLog::registerLogs('reachus.settings.updated', [
            'icon' => 'sliders',
            'color' => 'warning',
            'message' => 'reachus::admin.logs.settings_updated',
        ]);
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
        return [
            'reachus' => [
                'name' => trans('reachus::admin.title'),
                'type' => 'dropdown',
                'icon' => 'bi bi-chat-left-text',
                'permission' => ['reachus.responses', 'reachus.settings'],
                'route' => 'reachus.admin.*',
                'items' => [
                    'reachus.admin.responses.index' => [
                        'name' => trans('reachus::admin.nav.responses'),
                        'permission' => 'reachus.responses',
                    ],
                    'reachus.admin.settings' => [
                        'name' => trans('reachus::admin.nav.settings'),
                        'permission' => 'reachus.settings',
                    ],
                ],
            ],
        ];
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
