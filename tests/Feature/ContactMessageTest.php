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
            'name', 'contact_method', 'contact_value', 'reason', 'read_at',
        ]));
    }

    public function test_contact_message_can_be_stored_and_marked_read(): void
    {
        $message = ContactMessage::create([
            'name' => 'Guest User', 'contact_method' => 'telegram',
            'contact_value' => '@guest', 'reason' => 'I have a question.',
        ]);

        $this->assertNull($message->read_at);

        $message->update(['read_at' => now()]);

        $this->assertNotNull($message->fresh()->read_at);
    }
}
