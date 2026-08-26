<?php

namespace Azuriom\Plugin\ReachUs\Controllers;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\ReachUs\Models\ContactMessage;
use Azuriom\Plugin\ReachUs\Requests\ContactRequest;
use Azuriom\Plugin\ReachUs\Services\ContactNotificationService;
use Azuriom\Plugin\ReachUs\Services\ReachUsSettings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Display the public contact page.
     */
    public function index(ReachUsSettings $settings): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->to($settings->authenticatedRedirect());
        }

        return view('reachus::index');
    }

    public function store(
        ContactRequest $request,
        ReachUsSettings $settings,
        ContactNotificationService $notifications,
    ): RedirectResponse
    {
        if ($request->user() !== null) {
            return redirect()->to($settings->authenticatedRedirect());
        }

        $message = ContactMessage::create($request->validated());
        $notifications->send($message);

        return to_route('reachus.index')
            ->with('success', trans('reachus::messages.form.sent'));
    }
}
