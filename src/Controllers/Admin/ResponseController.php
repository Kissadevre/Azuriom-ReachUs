<?php

namespace Azuriom\Plugin\ReachUs\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Models\ActionLog;
use Azuriom\Plugin\ReachUs\Models\ContactMessage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ResponseController extends Controller
{
    public function index(): View
    {
        return view('reachus::admin.responses.index', [
            'messages' => ContactMessage::latest()->paginate(20),
            'unreadCount' => ContactMessage::whereNull('read_at')->count(),
        ]);
    }

    public function show(ContactMessage $message): View
    {
        if ($message->read_at === null) {
            $message->update(['read_at' => now()]);
        }

        return view('reachus::admin.responses.show', ['message' => $message]);
    }

    public function unread(ContactMessage $message): RedirectResponse
    {
        $message->update(['read_at' => null]);

        return to_route('reachus.admin.responses.index')
            ->with('success', trans('reachus::admin.responses.marked_unread'));
    }

    public function destroy(ContactMessage $message): RedirectResponse
    {
        $messageId = $message->id;
        $message->delete();

        ActionLog::log('reachus.responses.deleted', null, ['message_id' => $messageId]);

        return to_route('reachus.admin.responses.index')
            ->with('success', trans('reachus::admin.responses.deleted'));
    }
}
