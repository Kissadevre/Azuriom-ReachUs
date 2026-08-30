<?php

namespace Azuriom\Plugin\ReachUs\Services;

use Azuriom\Models\Page;
use Azuriom\Models\Post;
use Illuminate\Support\Facades\Route;

class ReachUsSettings
{
    public const RATE_LIMIT_KEY = 'reachus.rate_limit';

    public const SUBMISSIONS_ENABLED_KEY = 'reachus.submissions_enabled';

    public const AUTHENTICATED_REDIRECT_KEY = 'reachus.authenticated_redirect';

    public const AUTHENTICATED_REDIRECT_TYPE_KEY = 'reachus.authenticated_redirect_type';

    public const AUTHENTICATED_REDIRECT_VALUE_KEY = 'reachus.authenticated_redirect_value';

    public const TERMS_ENABLED_KEY = 'reachus.terms_enabled';

    public const TERMS_TEXT_KEY = 'reachus.terms_text';

    public const TERMS_URL_KEY = 'reachus.terms_url';

    public const DISCORD_WEBHOOK_ENABLED_KEY = 'reachus.discord_webhook.enabled';

    public const DISCORD_WEBHOOK_URL_KEY = 'reachus.discord_webhook.url';

    public const DEFAULT_RATE_LIMIT = 5;

    public const DEFAULT_AUTHENTICATED_REDIRECT = '/';

    public const DEFAULT_AUTHENTICATED_REDIRECT_TYPE = 'link';

    private const REDIRECT_TYPES = [
        'link', 'page', 'post', 'posts', 'plugin',
    ];

    public function rateLimit(): int
    {
        return max(1, min(100, (int) setting(self::RATE_LIMIT_KEY, self::DEFAULT_RATE_LIMIT)));
    }

    public function submissionsEnabled(): bool
    {
        return filter_var(setting(self::SUBMISSIONS_ENABLED_KEY, true), FILTER_VALIDATE_BOOL);
    }

    public function termsEnabled(): bool
    {
        return filter_var(setting(self::TERMS_ENABLED_KEY, false), FILTER_VALIDATE_BOOL);
    }

    public function termsText(): string
    {
        $text = setting(self::TERMS_TEXT_KEY, '');

        return is_string($text) ? trim($text) : '';
    }

    public function termsUrl(): string
    {
        $url = setting(self::TERMS_URL_KEY, '');

        return is_string($url) ? trim($url) : '';
    }

    public function termsRequired(): bool
    {
        return $this->termsEnabled()
            && $this->termsText() !== ''
            && self::isAllowedLink($this->termsUrl());
    }

    public function discordWebhookEnabled(): bool
    {
        return filter_var(setting(self::DISCORD_WEBHOOK_ENABLED_KEY, false), FILTER_VALIDATE_BOOL);
    }

    public function discordWebhookUrl(): ?string
    {
        $url = setting(self::DISCORD_WEBHOOK_URL_KEY);

        return is_string($url) && trim($url) !== '' ? trim($url) : null;
    }

    public function authenticatedRedirectType(): string
    {
        $type = setting(self::AUTHENTICATED_REDIRECT_TYPE_KEY);

        return is_string($type) && in_array($type, self::REDIRECT_TYPES, true)
            ? $type
            : self::DEFAULT_AUTHENTICATED_REDIRECT_TYPE;
    }

    public function authenticatedRedirectValue(): string
    {
        $value = setting(self::AUTHENTICATED_REDIRECT_VALUE_KEY);

        if (is_string($value) && $value !== '') {
            return $value;
        }

        $legacyValue = setting(self::AUTHENTICATED_REDIRECT_KEY);

        return is_string($legacyValue) && $legacyValue !== ''
            ? $legacyValue
            : self::DEFAULT_AUTHENTICATED_REDIRECT;
    }

    public function authenticatedRedirect(): string
    {
        $type = $this->authenticatedRedirectType();
        $value = $this->authenticatedRedirectValue();

        return match ($type) {
            'link' => self::isAllowedLink($value) ? $value : self::DEFAULT_AUTHENTICATED_REDIRECT,
            'page' => Page::enabled()->where('slug', $value)->exists()
                ? route('pages.show', $value)
                : self::DEFAULT_AUTHENTICATED_REDIRECT,
            'post' => Post::published()->where('slug', $value)->exists()
                ? route('posts.show', $value)
                : self::DEFAULT_AUTHENTICATED_REDIRECT,
            'posts' => Route::has('posts.index')
                ? route('posts.index')
                : self::DEFAULT_AUTHENTICATED_REDIRECT,
            'plugin' => $value !== 'reachus.index' && Route::has($value)
                ? route($value)
                : self::DEFAULT_AUTHENTICATED_REDIRECT,
            default => self::DEFAULT_AUTHENTICATED_REDIRECT,
        };
    }

    public static function redirectTypes(): array
    {
        return self::REDIRECT_TYPES;
    }

    public static function isAllowedLink(mixed $value): bool
    {
        if (! is_string($value) || $value === '' || str_contains($value, "\r") || str_contains($value, "\n")) {
            return false;
        }

        if (preg_match('/^\/(?!\/)/', $value) === 1) {
            return true;
        }

        return filter_var($value, FILTER_VALIDATE_URL) !== false
            && in_array(parse_url($value, PHP_URL_SCHEME), ['http', 'https'], true);
    }
}
