<?php

namespace Azuriom\Plugin\ReachUs\Middleware;

use Azuriom\Plugin\ReachUs\Services\ReachUsSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAuthenticatedUsers
{
    public function __construct(private readonly ReachUsSettings $settings)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() !== null) {
            return redirect()->to($this->settings->authenticatedRedirect());
        }

        return $next($request);
    }
}
