<?php

namespace Azuriom\Plugin\ReachUs\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Page;
use Azuriom\Models\Post;
use Azuriom\Models\Setting;
use Azuriom\Plugin\ReachUs\Services\ContactChannelService;
use Azuriom\Plugin\ReachUs\Services\ReachUsSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function show(ReachUsSettings $settings, ContactChannelService $channels): View
    {
        return view('reachus::admin.settings', [
            'rateLimit' => $settings->rateLimit(),
            'submissionsEnabled' => $settings->submissionsEnabled(),
            'termsEnabled' => $settings->termsEnabled(),
            'termsText' => $settings->termsText(),
            'termsUrl' => $settings->termsUrl(),
            'contactChannels' => $channels->channels(),
            'maxContactChannels' => ContactChannelService::MAX_CHANNELS,
            'redirectTypes' => ReachUsSettings::redirectTypes(),
            'redirectType' => $settings->authenticatedRedirectType(),
            'redirectValue' => $settings->authenticatedRedirectValue(),
            'pages' => Page::enabled()->orderBy('title')->get(),
            'posts' => Post::published()->latest('published_at')->get(),
            'pluginRoutes' => $this->pluginRoutes(),
        ]);
    }

    public function save(Request $request): RedirectResponse
    {
        $pluginRoutes = $this->pluginRoutes();
        $validated = $request->validate([
            'rate_limit' => ['required', 'regex:/^[0-9]+$/D', 'integer', 'min:1', 'max:100'],
            'submissions_enabled' => ['required', 'boolean'],
            'terms_enabled' => ['required', 'boolean'],
            'terms_text' => ['required_if:terms_enabled,1', 'nullable', 'string', 'max:200'],
            'terms_url' => [
                'required_if:terms_enabled,1', 'nullable', 'string', 'max:2048',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value !== null && ! ReachUsSettings::isAllowedLink($value)) {
                        $fail(trans('reachus::admin.settings.terms_url_format'));
                    }
                },
            ],
            'channels' => ['required', 'array', 'min:1', 'max:'.ContactChannelService::MAX_CHANNELS],
            'channels.*.id' => [
                'required', 'string', 'distinct:strict', 'max:20', 'regex:/^[a-z0-9][a-z0-9_-]*$/D',
            ],
            'channels.*.name' => ['required', 'string', 'max:64'],
            'channels.*.icon' => [
                'required', 'string', 'max:64', 'regex:/^bi bi-[a-z0-9]+(?:-[a-z0-9]+)*$/D',
            ],
            'redirect_type' => ['required', Rule::in(ReachUsSettings::redirectTypes())],
            'redirect_link' => [
                'required_if:redirect_type,link', 'nullable', 'string', 'max:2048',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value !== null && ! ReachUsSettings::isAllowedLink($value)) {
                        $fail(trans('reachus::admin.settings.redirect_link_format'));
                    }
                },
            ],
            'redirect_page' => [
                'required_if:redirect_type,page', 'nullable', 'integer',
                Rule::exists(Page::class, 'id')->where('is_enabled', true),
            ],
            'redirect_post' => [
                'required_if:redirect_type,post', 'nullable', 'integer',
                Rule::exists(Post::class, 'id')->where(fn ($query) => $query
                    ->whereNotNull('published_at')->where('published_at', '<=', now())),
            ],
            'redirect_plugin' => [
                'required_if:redirect_type,plugin', 'nullable', Rule::in($pluginRoutes->keys()->all()),
            ],
        ], [
            'channels.*.id.regex' => trans('reachus::admin.settings.channel_identifier_format'),
            'channels.*.icon.regex' => trans('reachus::admin.settings.channel_icon_format'),
        ]);

        $type = $validated['redirect_type'];
        $value = match ($type) {
            'link' => $validated['redirect_link'],
            'page' => Page::findOrFail($validated['redirect_page'])->slug,
            'post' => Post::findOrFail($validated['redirect_post'])->slug,
            'posts' => '#',
            'plugin' => $validated['redirect_plugin'],
        };
        $channels = collect($validated['channels'])->map(fn (array $channel) => [
            'id' => $channel['id'],
            'name' => trim($channel['name']),
            'icon' => $channel['icon'],
        ])->values()->all();

        Setting::updateSettings([
            ReachUsSettings::RATE_LIMIT_KEY => (int) $validated['rate_limit'],
            ReachUsSettings::SUBMISSIONS_ENABLED_KEY => (bool) $validated['submissions_enabled'],
            ReachUsSettings::TERMS_ENABLED_KEY => (bool) $validated['terms_enabled'],
            ReachUsSettings::TERMS_TEXT_KEY => $validated['terms_text'] ?? '',
            ReachUsSettings::TERMS_URL_KEY => $validated['terms_url'] ?? '',
            ContactChannelService::SETTINGS_KEY => json_encode($channels, JSON_THROW_ON_ERROR),
            ReachUsSettings::AUTHENTICATED_REDIRECT_TYPE_KEY => $type,
            ReachUsSettings::AUTHENTICATED_REDIRECT_VALUE_KEY => $value,
            ReachUsSettings::AUTHENTICATED_REDIRECT_KEY => null,
        ]);
        ActionLog::log('reachus.settings.updated');

        return to_route('reachus.admin.settings')
            ->with('success', trans('reachus::admin.settings.updated'));
    }

    private function pluginRoutes(): Collection
    {
        return plugins()->getRouteDescriptions()->except(['reachus.index']);
    }
}
