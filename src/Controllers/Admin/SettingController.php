<?php

namespace Azuriom\Plugin\ReachUs\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Setting;
use Azuriom\Plugin\ReachUs\Services\ReachUsSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function show(ReachUsSettings $settings): View
    {
        return view('reachus::admin.settings', [
            'rateLimit' => $settings->rateLimit(),
            'authenticatedRedirect' => $settings->authenticatedRedirect(),
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'rate_limit' => ['required', 'regex:/^[0-9]+$/D', 'integer', 'min:1', 'max:100'],
                'authenticated_redirect' => ['required', 'string', 'max:2048', 'regex:/^\/(?!\/)[^\r\n]*$/'],
            ],
            ['authenticated_redirect.regex' => trans('reachus::admin.settings.redirect_format')],
        );

        Setting::updateSettings([
            ReachUsSettings::RATE_LIMIT_KEY => (int) $validated['rate_limit'],
            ReachUsSettings::AUTHENTICATED_REDIRECT_KEY => $validated['authenticated_redirect'],
        ]);
        ActionLog::log('reachus.settings.updated');

        return to_route('reachus.admin.settings')
            ->with('success', trans('reachus::admin.settings.updated'));
    }
}
