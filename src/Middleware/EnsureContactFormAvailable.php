<?php

namespace Azuriom\Plugin\ReachUs\Middleware;

use Azuriom\Plugin\ReachUs\Services\ReachUsSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureContactFormAvailable
{
    public function __construct(private readonly ReachUsSettings $settings)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->settings->submissionsEnabled()) {
            return to_route('reachus.index');
        }

        return $next($request);
    }
}
