<?php

namespace Azuriom\Plugin\ReachUs\Tests\Feature;

use Azuriom\Models\Setting;
use Azuriom\Plugin\ReachUs\Providers\ReachUsServiceProvider;
use Azuriom\Plugin\ReachUs\Services\ReachUsSettings;
use Azuriom\Plugin\ReachUs\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ReachUsSecurityTest extends TestCase
{
    public function test_submission_route_uses_rate_limit_and_azuriom_captcha(): void
    {
        $router = $this->app->make('router');
        $router->prefix('reachus')->name('reachus.')->group(dirname(__DIR__, 2).'/routes/web.php');
        $router->getRoutes()->refreshNameLookups();

        $route = $router->getRoutes()->getByName('reachus.store');

        $this->assertNotNull($route);
        $this->assertContains('throttle:reachus.contact', $route->gatherMiddleware());
        $this->assertContains('captcha', $route->gatherMiddleware());
    }

    public function test_form_uses_the_shared_azuriom_captcha_element(): void
    {
        $view = file_get_contents(dirname(__DIR__, 2).'/resources/views/index.blade.php');

        $this->assertStringContainsString('id="captcha-form"', $view);
        $this->assertStringContainsString("@include('elements.captcha', ['center' => true])", $view);
    }

    public function test_administration_routes_enforce_section_permissions(): void
    {
        $router = $this->app->make('router');
        $router->prefix('admin/reachus')
            ->name('reachus.admin.')
            ->group(dirname(__DIR__, 2).'/routes/admin.php');
        $router->getRoutes()->refreshNameLookups();

        $responses = $router->getRoutes()->getByName('reachus.admin.responses.index');
        $settings = $router->getRoutes()->getByName('reachus.admin.settings');

        $this->assertNotNull($responses);
        $this->assertNotNull($settings);
        $this->assertContains('can:reachus.responses', $responses->gatherMiddleware());
        $this->assertContains('can:reachus.settings', $settings->gatherMiddleware());
    }

    public function test_rate_limiter_uses_current_setting_and_ip_address(): void
    {
        Setting::updateSettings(ReachUsSettings::RATE_LIMIT_KEY, 7);

        if (RateLimiter::limiter('reachus.contact') === null) {
            $provider = new ReachUsServiceProvider($this->app);
            (new \ReflectionMethod($provider, 'registerRateLimiter'))->invoke($provider);
        }

        $limiter = RateLimiter::limiter('reachus.contact');
        $request = Request::create('/reachus', 'POST', server: ['REMOTE_ADDR' => '192.0.2.20']);
        $limit = $limiter($request);

        $this->assertSame(7, $limit->maxAttempts);
        $this->assertSame(60, $limit->decaySeconds);
        $this->assertSame('reachus.contact|192.0.2.20', $limit->key);
    }

    public function test_authenticated_redirect_is_restricted_to_a_local_path(): void
    {
        $settings = app(ReachUsSettings::class);

        Setting::updateSettings(ReachUsSettings::AUTHENTICATED_REDIRECT_KEY, '/support/tickets');
        $this->assertSame('/support/tickets', $settings->authenticatedRedirect());

        Setting::updateSettings(ReachUsSettings::AUTHENTICATED_REDIRECT_KEY, '//evil.example');
        $this->assertSame('/', $settings->authenticatedRedirect());
    }
}
