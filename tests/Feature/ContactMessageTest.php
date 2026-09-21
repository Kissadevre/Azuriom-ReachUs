<?php

namespace Azuriom\Plugin\ReachUs\Tests\Feature;

use Azuriom\Plugin\ReachUs\Models\ContactMessage;
use Azuriom\Plugin\ReachUs\Tests\TestCase;
use Illuminate\Support\Facades\Schema;

class ContactMessageTest extends TestCase
{
    public function test_plugin_migration_creates_the_contact_message_table(): void
    {
        $this->assertTrue(Schema::hasTable('reachus_contact_messages'));
        $this->assertTrue(Schema::hasColumns('reachus_contact_messages', [
            'name', 'contact_method', 'contact_channel_name', 'contact_channel_icon',
            'contact_value', 'reason', 'read_at',
        ]));
    }

    public function test_plugin_baseline_migration_can_be_rolled_back(): void
    {
        $migrationPath = dirname(__DIR__, 2).'/database/migrations/';

        (require $migrationPath.'2026_08_26_000000_create_reachus_contact_messages_table.php')->down();

        $this->assertFalse(Schema::hasTable('reachus_contact_messages'));
    }

    public function test_contact_message_can_be_stored_and_marked_read(): void
    {
        $message = ContactMessage::create([
            'name' => 'Guest User', 'contact_method' => 'telegram',
            'contact_channel_name' => 'Telegram', 'contact_channel_icon' => 'bi bi-telegram',
            'contact_value' => '@guest', 'reason' => 'I have a question.',
        ]);

        $this->assertNull($message->read_at);
        $this->assertSame('Telegram', $message->contact_channel_name);
        $this->assertSame('bi bi-telegram', $message->contact_channel_icon);

        $message->update(['read_at' => now()]);

        $this->assertNotNull($message->fresh()->read_at);
    }
}
