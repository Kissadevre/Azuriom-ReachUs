<?php

namespace Azuriom\Plugin\ReachUs\Tests\Feature;

use Azuriom\Models\Setting;
use Azuriom\Plugin\ReachUs\Models\ContactMessage;
use Azuriom\Plugin\ReachUs\Providers\ReachUsServiceProvider;
use Azuriom\Plugin\ReachUs\Services\DiscordWebhookService;
use Azuriom\Plugin\ReachUs\Services\ReachUsSettings;
use Azuriom\Plugin\ReachUs\Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DiscordWebhookTest extends TestCase
{
    public function test_discord_notifications_are_disabled_by_default(): void
    {
        Http::fake();

        $message = $this->createMessage();

        $this->assertFalse(app(ReachUsSettings::class)->discordWebhookEnabled());
        $this->assertNull(app(DiscordWebhookService::class)->notifyNewMessage($message));
        Http::assertNothingSent();
    }

    public function test_only_official_discord_webhook_urls_are_accepted(): void
    {
        foreach ([
            'https://discord.com/api/webhooks/123456789/test-token',
            'https://canary.discord.com/api/v10/webhooks/123456789/test_token.with-dots',
            'https://discordapp.com/api/webhooks/123456789/test-token',
        ] as $url) {
            $this->assertTrue(DiscordWebhookService::isValidUrl($url));
        }

        foreach ([
            'http://discord.com/api/webhooks/123456789/test-token',
            'https://example.com/api/webhooks/123456789/test-token',
            'https://discord.com.evil.test/api/webhooks/123456789/test-token',
            'https://attacker@discord.com/api/webhooks/123456789/test-token',
            'https://discord.com:8443/api/webhooks/123456789/test-token',
            'https://discord.com/api/webhooks/123456789/test-token?wait=false',
            'https://discord.com/login',
            'not-a-url',
        ] as $url) {
            $this->assertFalse(DiscordWebhookService::isValidUrl($url));
        }
    }

    public function test_notification_contains_only_generic_message_information_and_timestamp(): void
    {
        Http::fake(['discord.com/*' => Http::response(status: 200)]);
        Setting::updateSettings([
            ReachUsSettings::DISCORD_WEBHOOK_ENABLED_KEY => true,
            ReachUsSettings::DISCORD_WEBHOOK_URL_KEY => 'https://discord.com/api/webhooks/123456789/test-token',
        ]);
        $message = $this->createMessage();

        app(DiscordWebhookService::class)->notifyNewMessage($message);

        Http::assertSent(function ($request) use ($message) {
            $payload = $request->data();
            $serialized = json_encode($payload, JSON_THROW_ON_ERROR);

            return $request->url() === 'https://discord.com/api/webhooks/123456789/test-token?wait=true'
                && data_get($payload, 'embeds.0.title') === trans('reachus::messages.webhook.new_message_title')
                && data_get($payload, 'embeds.0.timestamp') === $message->created_at->toAtomString()
                && ! str_contains($serialized, 'Private Guest')
                && ! str_contains($serialized, 'private@example.com')
                && ! str_contains($serialized, 'Sensitive question')
                && ! str_contains($serialized, 'Email');
        });
    }

    public function test_discord_failure_does_not_remove_or_reject_the_persisted_message(): void
    {
        Http::fake(['discord.com/*' => Http::response(['message' => 'Unavailable'], 503)]);
        Setting::updateSettings([
            ReachUsSettings::DISCORD_WEBHOOK_ENABLED_KEY => true,
            ReachUsSettings::DISCORD_WEBHOOK_URL_KEY => 'https://discord.com/api/webhooks/123456789/failing-token',
        ]);
        $message = $this->createMessage();

        $this->assertNull(app(DiscordWebhookService::class)->notifyNewMessage($message));
        $this->assertDatabaseHas('reachus_contact_messages', [
            'id' => $message->id,
            'contact_value' => 'private@example.com',
        ]);
    }

    public function test_manual_test_uses_the_administrator_provided_url(): void
    {
        Http::fake(['discord.com/*' => Http::response(status: 200)]);
        $url = 'https://discord.com/api/webhooks/123456789/test-token';

        app(DiscordWebhookService::class)->sendTest($url);

        Http::assertSent(fn ($request) => $request->url() === $url.'?wait=true'
            && data_get($request->data(), 'embeds.0.title') === trans('reachus::messages.webhook.test_title'));
    }

    public function test_webhook_url_is_encrypted_in_the_settings_table(): void
    {
        $url = 'https://discord.com/api/webhooks/123456789/secret-token';
        (new ReachUsServiceProvider($this->app))->register();

        Setting::updateSettings(ReachUsSettings::DISCORD_WEBHOOK_URL_KEY, $url);

        $stored = DB::table('settings')
            ->where('name', ReachUsSettings::DISCORD_WEBHOOK_URL_KEY)
            ->value('value');

        $this->assertNotSame($url, $stored);
        $this->assertStringNotContainsString('secret-token', $stored);
        $this->assertSame($url, setting(ReachUsSettings::DISCORD_WEBHOOK_URL_KEY));
    }

    private function createMessage(): ContactMessage
    {
        return ContactMessage::create([
            'name' => 'Private Guest',
            'contact_method' => 'email',
            'contact_channel_name' => 'Email',
            'contact_channel_icon' => 'bi bi-envelope',
            'contact_value' => 'private@example.com',
            'reason' => 'Sensitive question',
        ]);
    }
}
