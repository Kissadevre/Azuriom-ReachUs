<?php

namespace Azuriom\Plugin\ReachUs\Tests\Feature;

use Azuriom\Models\User;
use Azuriom\Plugin\ReachUs\Models\ContactMessage;
use Azuriom\Plugin\ReachUs\Services\ContactNotificationService;
use Azuriom\Plugin\ReachUs\Tests\TestCase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class ContactNotificationTest extends TestCase
{
    public function test_only_users_with_response_permission_or_admin_roles_are_notified(): void
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'Member', 'color' => 'ffffff', 'power' => 0, 'is_admin' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Responder', 'color' => 'ffffff', 'power' => 1, 'is_admin' => false, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Admin', 'color' => 'ffffff', 'power' => 2, 'is_admin' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
        DB::table('permissions')->insert(['role_id' => 2, 'permission' => 'reachus.responses']);

        foreach ([1, 2, 3] as $id) {
            DB::table('users')->insert([
                'id' => $id, 'name' => 'User '.$id, 'email' => 'user'.$id.'@example.com',
                'password' => 'unused', 'role_id' => $id, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        Route::get('/admin/reachus/responses/{message}', fn () => 'ok')
            ->name('reachus.admin.responses.show');
        Route::getRoutes()->refreshNameLookups();

        $message = ContactMessage::create([
            'name' => 'Guest User', 'contact_method' => 'email',
            'contact_value' => 'guest@example.com', 'reason' => 'A question.',
        ]);

        $recipients = User::registered()
            ->whereHas('role', function (Builder $query) {
                $query->where('is_admin', true)
                    ->orWhereHas('permissions', fn (Builder $permissions) => $permissions
                        ->where('permission', 'reachus.responses'));
            })
            ->pluck('id')
            ->all();

        $this->assertSame([2, 3], $recipients);

        app(ContactNotificationService::class)->send($message);

        $this->assertDatabaseMissing('notifications', ['user_id' => 1]);
        $this->assertDatabaseHas('notifications', ['user_id' => 2]);
        $this->assertDatabaseHas('notifications', ['user_id' => 3]);
        $this->assertSame(2, DB::table('notifications')->count());
    }
}
