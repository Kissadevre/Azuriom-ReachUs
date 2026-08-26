<?php

namespace Azuriom\Plugin\ReachUs\Services;

use Azuriom\Models\User;
use Azuriom\Notifications\AlertNotification;
use Azuriom\Plugin\ReachUs\Models\ContactMessage;
use Illuminate\Database\Eloquent\Builder;

class ContactNotificationService
{
    public function send(ContactMessage $message): void
    {
        User::registered()
            ->whereHas('role', function (Builder $query) {
                $query->where('is_admin', true)
                    ->orWhereHas('permissions', fn (Builder $permissions) => $permissions
                        ->where('permission', 'reachus.responses'));
            })
            ->eachById(function (User $user) use ($message) {
                rescue(function () use ($user, $message) {
                    (new AlertNotification(trans('reachus::messages.notifications.new_message', [
                        'name' => $message->name,
                    ])))
                        ->link(route('reachus.admin.responses.show', $message))
                        ->send($user);
                });
            });
    }
}
