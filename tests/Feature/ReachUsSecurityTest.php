<?php

namespace Azuriom\Plugin\ReachUs\Tests\Feature;

use Azuriom\Models\Setting;
use Azuriom\Plugin\ReachUs\Middleware\RedirectAuthenticatedUsers;
use Azuriom\Plugin\ReachUs\Providers\ReachUsServiceProvider;
use Azuriom\Plugin\ReachUs\Services\ReachUsSettings;
use Azuriom\Plugin\ReachUs\Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class ReachUsSecurityTest extends TestCase
{
    public function test_submission_route_uses_rate_limit_and_azuriom_captcha(): void
    {
        $router = $this->app->make('router');
        $router->prefix('reachus')->name('reachus.')->group(dirname(__DIR__, 2).'/routes/web.php');
        $router->getRoutes()->refreshNameLookups();

        $route = $router->getRoutes()->getByName('reachus.store');

        $this->assertNotNull($route);
        $this->assertContains(RedirectAuthenticatedUsers::class, $route->gatherMiddleware());
        $this->assertContains('throttle:reachus.contact', $route->gatherMiddleware());
        $this->assertContains('captcha', $route->gatherMiddleware());
    }

    public function test_form_uses_the_shared_azuriom_captcha_element(): void
    {
        $view = file_get_contents(dirname(__DIR__, 2).'/resources/views/index.blade.php');

        $this->assertStringContainsString('id="captcha-form"', $view);
        $this->assertStringContainsString("@include('elements.captcha', ['center' => true])", $view);
        $this->assertStringContainsString("input.removeAttribute('pattern')", $view);
        $this->assertStringContainsString("input.maxLength = selected === 'whatsapp' ? 16 : 255", $view);
        $this->assertStringContainsString("input.setAttribute('pattern', '[A-Za-z0-9_-]+')", $view);
        $this->assertStringContainsString("input.setAttribute('pattern', '[A-Za-z0-9@_-]+')", $view);
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

    public function test_settings_form_offers_the_navbar_style_destination_types(): void
    {
        $view = file_get_contents(dirname(__DIR__, 2).'/resources/views/admin/settings.blade.php');

        $this->assertStringContainsString('name="redirect_type"', $view);

        foreach (ReachUsSettings::redirectTypes() as $type) {
            $this->assertStringContainsString('data-redirect-field="'.$type.'"', $view);
        }

        $this->assertStringContainsString('name="redirect_page"', $view);
        $this->assertStringContainsString('name="redirect_post"', $view);
        $this->assertStringContainsString('name="redirect_plugin"', $view);
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

    public function test_authenticated_redirect_preserves_the_legacy_local_path(): void
    {
        $settings = app(ReachUsSettings::class);

        Setting::updateSettings(ReachUsSettings::AUTHENTICATED_REDIRECT_KEY, '/support/tickets');

        $this->assertSame('link', $settings->authenticatedRedirectType());
        $this->assertSame('/support/tickets', $settings->authenticatedRedirectValue());
        $this->assertSame('/support/tickets', $settings->authenticatedRedirect());
    }

    public function test_authenticated_redirect_resolves_navbar_style_destination_types(): void
    {
        $settings = app(ReachUsSettings::class);

        Setting::updateSettings([
            ReachUsSettings::AUTHENTICATED_REDIRECT_TYPE_KEY => 'link',
            ReachUsSettings::AUTHENTICATED_REDIRECT_VALUE_KEY => 'https://example.com/support',
        ]);
        $this->assertSame('https://example.com/support', $settings->authenticatedRedirect());

        DB::table('pages')->insert([
            'id' => 1, 'title' => 'Contact members', 'description' => 'Members',
            'slug' => 'members-contact', 'content' => 'Content', 'is_enabled' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        Setting::updateSettings([
            ReachUsSettings::AUTHENTICATED_REDIRECT_TYPE_KEY => 'page',
            ReachUsSettings::AUTHENTICATED_REDIRECT_VALUE_KEY => 'members-contact',
        ]);
        $this->assertSame(route('pages.show', 'members-contact'), $settings->authenticatedRedirect());

        DB::table('roles')->insert([
            'id' => 1, 'name' => 'Member', 'color' => 'ffffff', 'power' => 0,
            'is_admin' => false, 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'id' => 1, 'name' => 'Author', 'email' => 'author@example.com', 'password' => 'unused',
            'role_id' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('posts')->insert([
            'id' => 1, 'author_id' => 1, 'title' => 'Member news', 'description' => 'News',
            'slug' => 'member-news', 'content' => 'Content', 'is_pinned' => false,
            'published_at' => now()->subMinute(), 'created_at' => now(), 'updated_at' => now(),
        ]);
        Setting::updateSettings([
            ReachUsSettings::AUTHENTICATED_REDIRECT_TYPE_KEY => 'post',
            ReachUsSettings::AUTHENTICATED_REDIRECT_VALUE_KEY => 'member-news',
        ]);
        $this->assertSame(route('posts.show', 'member-news'), $settings->authenticatedRedirect());

        Setting::updateSettings([
            ReachUsSettings::AUTHENTICATED_REDIRECT_TYPE_KEY => 'posts',
            ReachUsSettings::AUTHENTICATED_REDIRECT_VALUE_KEY => '#',
        ]);
        $this->assertSame(route('posts.index'), $settings->authenticatedRedirect());

        Route::get('/member-area', fn () => 'ok')->name('member-area.index');
        Route::getRoutes()->refreshNameLookups();
        Setting::updateSettings([
            ReachUsSettings::AUTHENTICATED_REDIRECT_TYPE_KEY => 'plugin',
            ReachUsSettings::AUTHENTICATED_REDIRECT_VALUE_KEY => 'member-area.index',
        ]);
        $this->assertSame(route('member-area.index'), $settings->authenticatedRedirect());
    }

    public function test_authenticated_redirect_falls_back_when_the_destination_is_unsafe_or_missing(): void
    {
        $settings = app(ReachUsSettings::class);

        Setting::updateSettings([
            ReachUsSettings::AUTHENTICATED_REDIRECT_TYPE_KEY => 'link',
            ReachUsSettings::AUTHENTICATED_REDIRECT_VALUE_KEY => '//evil.example',
        ]);
        $this->assertSame('/', $settings->authenticatedRedirect());

        Setting::updateSettings([
            ReachUsSettings::AUTHENTICATED_REDIRECT_TYPE_KEY => 'plugin',
            ReachUsSettings::AUTHENTICATED_REDIRECT_VALUE_KEY => 'reachus.index',
        ]);
        $this->assertSame('/', $settings->authenticatedRedirect());
    }
}
