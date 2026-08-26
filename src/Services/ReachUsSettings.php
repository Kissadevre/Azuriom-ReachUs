<?php

namespace Azuriom\Plugin\ReachUs\Services;

class ReachUsSettings
{
    public const RATE_LIMIT_KEY = 'reachus.rate_limit';
    public const AUTHENTICATED_REDIRECT_KEY = 'reachus.authenticated_redirect';
    public const DEFAULT_RATE_LIMIT = 5;
    public const DEFAULT_AUTHENTICATED_REDIRECT = '/';

    public function rateLimit(): int
    {
        return max(1, min(100, (int) setting(self::RATE_LIMIT_KEY, self::DEFAULT_RATE_LIMIT)));
    }

    public function authenticatedRedirect(): string
    {
        $path = setting(self::AUTHENTICATED_REDIRECT_KEY, self::DEFAULT_AUTHENTICATED_REDIRECT);

        if (! is_string($path) || preg_match('/^\/(?!\/)[^\r\n]*$/', $path) !== 1) {
            return self::DEFAULT_AUTHENTICATED_REDIRECT;
        }

        return $path;
    }
}
