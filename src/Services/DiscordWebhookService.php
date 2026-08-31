<?php

namespace Azuriom\Plugin\ReachUs\Services;

use Azuriom\Plugin\ReachUs\Models\ContactMessage;
use Azuriom\Support\Discord\DiscordWebhook;
use Azuriom\Support\Discord\Embed;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class DiscordWebhookService
{
    private const ALLOWED_HOSTS = [
        'discord.com',
        'ptb.discord.com',
        'canary.discord.com',
        'discordapp.com',
        'ptb.discordapp.com',
        'canary.discordapp.com',
    ];

    public function __construct(private readonly ReachUsSettings $settings) {}

    /**
     * Notify Discord after a contact message has already been persisted.
     *
     * Webhook failures are deliberately contained so they can never reject a
     * guest submission or remove it from the administration inbox.
     */
    public function notifyNewMessage(ContactMessage $message): ?Response
    {
        $url = $this->settings->discordWebhookUrl();

        if (! $this->settings->discordWebhookEnabled() || $url === null || ! self::isValidUrl($url)) {
            return null;
        }

        try {
            return $this->sendNotification($url, $message->created_at ?? now());
        } catch (Throwable $exception) {
            Log::warning('Reach Us Discord webhook notification failed.', [
                'exception' => $exception::class,
            ]);

            return null;
        }
    }

    /**
     * Send a harmless message to verify an administrator-provided endpoint.
     */
    public function sendTest(string $url): Response
    {
        $embed = Embed::create()
            ->title(trans('reachus::messages.webhook.test_title'))
            ->description(trans('reachus::messages.webhook.test_description'))
            ->color('#5865F2')
            ->timestamp(now());

        return $this->send($url, $embed);
    }

    /**
     * Restrict outgoing requests to official Discord webhook endpoints.
     */
    public static function isValidUrl(mixed $url): bool
    {
        if (! is_string($url) || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return false;
        }

        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');
        $path = $parts['path'] ?? '';

        return ($parts['scheme'] ?? '') === 'https'
            && in_array($host, self::ALLOWED_HOSTS, true)
            && ! isset($parts['user'])
            && ! isset($parts['pass'])
            && ! isset($parts['port'])
            && ! isset($parts['query'])
            && ! isset($parts['fragment'])
            && preg_match('#^/api(?:/v[0-9]+)?/webhooks/[0-9]+/[A-Za-z0-9._-]+/?$#D', $path) === 1;
    }

    private function sendNotification(string $url, \DateTimeInterface $receivedAt): Response
    {
        $embed = Embed::create()
            ->title(trans('reachus::messages.webhook.new_message_title'))
            ->description(trans('reachus::messages.webhook.new_message_description'))
            ->color('#5865F2')
            ->timestamp($receivedAt);

        return $this->send($url, $embed);
    }

    private function send(string $url, Embed $embed): Response
    {
        return DiscordWebhook::create()
            ->username('Reach Us')
            ->addEmbed($embed)
            ->send($url.'?wait=true');
    }
}
