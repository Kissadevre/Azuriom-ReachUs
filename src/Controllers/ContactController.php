<?php

namespace Azuriom\Plugin\ReachUs\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\ReachUs\Models\ContactMessage;
use Azuriom\Plugin\ReachUs\Requests\ContactRequest;
use Azuriom\Plugin\ReachUs\Services\ContactChannelService;
use Azuriom\Plugin\ReachUs\Services\ContactNotificationService;
use Azuriom\Plugin\ReachUs\Services\ReachUsSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Display the public contact page.
     */
    public function index(
        ReachUsSettings $settings,
        ContactChannelService $channels,
    ): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->to($settings->authenticatedRedirect());
        }

        return view('reachus::index', [
            'submissionsEnabled' => $settings->submissionsEnabled(),
            'termsRequired' => $settings->termsRequired(),
            'termsText' => $settings->termsText(),
            'termsUrl' => $settings->termsUrl(),
            'contactChannels' => $channels->channels(),
            'contactFields' => $channels->fieldConfigurations(),
        ]);
    }

    public function store(
        ContactRequest $request,
        ReachUsSettings $settings,
        ContactChannelService $channels,
        ContactNotificationService $notifications,
    ): RedirectResponse
    {
        if ($request->user() !== null) {
            return redirect()->to($settings->authenticatedRedirect());
        }

        $channel = $channels->find($request->string('contact_method')->toString()) ?? [
            'name' => $request->string('contact_method')->toString(),
            'icon' => 'bi bi-chat',
        ];
        $message = ContactMessage::create(array_merge($request->safe()->only([
            'name', 'contact_method', 'contact_value', 'reason',
        ]), [
            'contact_channel_name' => $channel['name'],
            'contact_channel_icon' => $channel['icon'],
        ]));
        $notifications->send($message);

        return to_route('reachus.index')
            ->with('success', trans('reachus::messages.form.sent'));
    }
}
