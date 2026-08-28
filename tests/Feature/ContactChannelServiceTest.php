<?php

namespace Azuriom\Plugin\ReachUs\Tests\Feature;

use Azuriom\Models\Setting;
use Azuriom\Plugin\ReachUs\Services\ContactChannelService;
use Azuriom\Plugin\ReachUs\Tests\TestCase;

class ContactChannelServiceTest extends TestCase
{
    public function test_default_contact_channels_are_available_in_the_expected_order(): void
    {
        $channels = app(ContactChannelService::class)->channels();

        $this->assertSame(['telegram', 'whatsapp', 'email', 'discord'], array_column($channels, 'id'));
        $this->assertSame('bi bi-telegram', $channels[0]['icon']);
        $this->assertCount(ContactChannelService::MAX_CHANNELS, $channels);
    }

    public function test_configured_channels_can_be_renamed_removed_and_replaced(): void
    {
        Setting::updateSettings(ContactChannelService::SETTINGS_KEY, json_encode([
            ['id' => 'whatsapp', 'name' => 'Phone', 'icon' => 'bi bi-telephone'],
            ['id' => 'custom_signal', 'name' => 'Signal', 'icon' => 'bi bi-chat-dots'],
        ], JSON_THROW_ON_ERROR));

        $service = app(ContactChannelService::class);

        $this->assertSame(['whatsapp', 'custom_signal'], $service->identifiers());
        $this->assertSame('Phone', $service->find('whatsapp')['name']);
        $this->assertSame('bi bi-chat-dots', $service->find('custom_signal')['icon']);
        $this->assertNull($service->find('telegram'));
        $this->assertSame('Phone contact details', $service->fieldConfigurations()['whatsapp']['label']);
        $this->assertSame(
            'Enter the details we should use to contact you through this channel.',
            $service->fieldConfigurations()['custom_signal']['help'],
        );
    }

    public function test_invalid_or_oversized_channel_configuration_falls_back_to_defaults(): void
    {
        foreach ([
            'invalid json',
            json_encode([['id' => 'unsafe', 'name' => 'Unsafe', 'icon' => 'text-danger onclick']], JSON_THROW_ON_ERROR),
            json_encode(array_fill(0, ContactChannelService::MAX_CHANNELS + 1, [
                'id' => 'duplicate', 'name' => 'Duplicate', 'icon' => 'bi bi-chat',
            ]), JSON_THROW_ON_ERROR),
        ] as $configuration) {
            Setting::updateSettings(ContactChannelService::SETTINGS_KEY, $configuration);

            $this->assertSame(ContactChannelService::defaults(), app(ContactChannelService::class)->channels());
        }

        $this->assertTrue(ContactChannelService::isAllowedIcon('bi bi-person-lines-fill'));
        $this->assertFalse(ContactChannelService::isAllowedIcon('fa fa-user'));
        $this->assertFalse(ContactChannelService::isAllowedIcon('bi bi-chat text-danger'));
    }
}
