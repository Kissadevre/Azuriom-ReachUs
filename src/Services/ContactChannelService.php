<?php

namespace Azuriom\Plugin\ReachUs\Services;

use JsonException;

class ContactChannelService
{
    public const SETTINGS_KEY = 'reachus.contact_channels';
    public const MAX_CHANNELS = 4;

    private const DEFAULT_CHANNELS = [
        ['id' => 'telegram', 'name' => 'Telegram', 'icon' => 'bi bi-telegram'],
        ['id' => 'whatsapp', 'name' => 'WhatsApp', 'icon' => 'bi bi-whatsapp'],
        ['id' => 'email', 'name' => 'Email', 'icon' => 'bi bi-envelope'],
        ['id' => 'discord', 'name' => 'Discord', 'icon' => 'bi bi-discord'],
    ];

    public function channels(): array
    {
        $configured = setting(self::SETTINGS_KEY);

        if ($configured === null) {
            return self::DEFAULT_CHANNELS;
        }

        if (is_string($configured)) {
            try {
                $configured = json_decode($configured, true, flags: JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                return self::DEFAULT_CHANNELS;
            }
        }

        if (! is_array($configured) || $configured === [] || count($configured) > self::MAX_CHANNELS) {
            return self::DEFAULT_CHANNELS;
        }

        $channels = [];
        $identifiers = [];

        foreach ($configured as $channel) {
            if (! is_array($channel)
                || ! isset($channel['id'], $channel['name'], $channel['icon'])
                || ! self::isAllowedIdentifier($channel['id'])
                || ! is_string($channel['name'])
                || trim($channel['name']) === ''
                || mb_strlen(trim($channel['name'])) > 64
                || ! self::isAllowedIcon($channel['icon'])
                || in_array($channel['id'], $identifiers, true)) {
                return self::DEFAULT_CHANNELS;
            }

            $channels[] = [
                'id' => $channel['id'],
                'name' => trim($channel['name']),
                'icon' => $channel['icon'],
            ];
            $identifiers[] = $channel['id'];
        }

        return $channels;
    }

    public function identifiers(): array
    {
        return array_column($this->channels(), 'id');
    }

    public function find(string $identifier): ?array
    {
        foreach ($this->channels() as $channel) {
            if ($channel['id'] === $identifier) {
                return $channel;
            }
        }

        return null;
    }

    public function fieldConfigurations(): array
    {
        $configurations = [];

        foreach ($this->channels() as $channel) {
            $translated = trans('reachus::messages.contact_fields.'.$channel['id']);
            $configurations[$channel['id']] = [
                'label' => trans('reachus::messages.form.contact_value_for', ['channel' => $channel['name']]),
                'help' => is_array($translated)
                    ? $translated['help']
                    : trans('reachus::messages.form.custom_contact_help'),
            ];
        }

        return $configurations;
    }

    public static function defaults(): array
    {
        return self::DEFAULT_CHANNELS;
    }

    public static function isAllowedIdentifier(mixed $identifier): bool
    {
        return is_string($identifier)
            && preg_match('/^[a-z0-9][a-z0-9_-]{0,19}$/D', $identifier) === 1;
    }

    public static function isAllowedIcon(mixed $icon): bool
    {
        return is_string($icon)
            && preg_match('/^bi bi-[a-z0-9]+(?:-[a-z0-9]+)*$/D', $icon) === 1
            && strlen($icon) <= 64;
    }
}
