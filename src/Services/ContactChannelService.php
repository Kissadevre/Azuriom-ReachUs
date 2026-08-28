<?php

namespace Azuriom\Plugin\ReachUs\Services;

use JsonException;

class ContactChannelService
{
    public const SETTINGS_KEY = 'reachus.contact_channels';
    public const MAX_CHANNELS = 4;
    public const TYPE_TEXT = 'text';
    public const TYPE_ALPHANUMERIC = 'alphanumeric';
    public const TYPE_NUMERIC = 'numeric';
    public const MIN_LENGTH = 1;
    public const MAX_LENGTH = 255;

    private const DEFAULT_CHANNELS = [
        ['id' => 'telegram', 'name' => 'Telegram', 'icon' => 'bi bi-telegram', 'data_type' => 'alphanumeric', 'min_length' => 1, 'max_length' => 255],
        ['id' => 'whatsapp', 'name' => 'WhatsApp', 'icon' => 'bi bi-whatsapp', 'data_type' => 'numeric', 'min_length' => 6, 'max_length' => 16],
        ['id' => 'email', 'name' => 'Email', 'icon' => 'bi bi-envelope', 'data_type' => 'text', 'min_length' => 3, 'max_length' => 255],
        ['id' => 'discord', 'name' => 'Discord', 'icon' => 'bi bi-discord', 'data_type' => 'alphanumeric', 'min_length' => 1, 'max_length' => 255],
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
                || ! isset($channel['id'], $channel['name'], $channel['icon'], $channel['data_type'], $channel['min_length'], $channel['max_length'])
                || ! self::isAllowedIdentifier($channel['id'])
                || ! is_string($channel['name'])
                || trim($channel['name']) === ''
                || mb_strlen(trim($channel['name'])) > 64
                || ! self::isAllowedIcon($channel['icon'])
                || ! in_array($channel['data_type'], self::dataTypes(), true)
                || ! is_int($channel['min_length'])
                || ! is_int($channel['max_length'])
                || $channel['min_length'] < self::MIN_LENGTH
                || $channel['max_length'] > self::MAX_LENGTH
                || $channel['max_length'] < $channel['min_length']
                || in_array($channel['id'], $identifiers, true)) {
                return self::DEFAULT_CHANNELS;
            }

            $channels[] = [
                'id' => $channel['id'],
                'name' => trim($channel['name']),
                'icon' => $channel['icon'],
                'data_type' => $channel['data_type'],
                'min_length' => $channel['min_length'],
                'max_length' => $channel['max_length'],
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
            $profile = self::validationProfile($channel);
            $configurations[$channel['id']] = [
                'label' => trans('reachus::messages.form.contact_value_for', ['channel' => $channel['name']]),
                'help' => trans('reachus::messages.contact_types.'.$profile, [
                    'min' => $channel['min_length'],
                    'max' => $channel['max_length'],
                ]),
                'htmlType' => $profile === 'email' ? 'email' : 'text',
                'inputMode' => match ($profile) {
                    'email' => 'email',
                    'whatsapp' => 'tel',
                    self::TYPE_NUMERIC => 'numeric',
                    default => 'text',
                },
                'minLength' => $channel['min_length'],
                'maxLength' => $channel['max_length'],
                'pattern' => match ($profile) {
                    'whatsapp' => '\\+?[0-9]+',
                    'username' => '[A-Za-z0-9_-]+',
                    'email' => '[A-Za-z0-9@_-]+',
                    self::TYPE_ALPHANUMERIC => '[\\p{L}\\p{N}]+',
                    self::TYPE_NUMERIC => '[0-9]+',
                    default => null,
                },
                'filter' => $profile,
            ];
        }

        return $configurations;
    }

    public static function defaults(): array
    {
        return self::DEFAULT_CHANNELS;
    }

    public static function dataTypes(): array
    {
        return [self::TYPE_TEXT, self::TYPE_ALPHANUMERIC, self::TYPE_NUMERIC];
    }

    public static function validationProfile(array $channel): string
    {
        if ($channel['id'] === 'whatsapp' && $channel['data_type'] === self::TYPE_NUMERIC) {
            return 'whatsapp';
        }

        if (in_array($channel['id'], ['telegram', 'discord'], true)
            && $channel['data_type'] === self::TYPE_ALPHANUMERIC) {
            return 'username';
        }

        if ($channel['id'] === 'email' && $channel['data_type'] === self::TYPE_TEXT) {
            return 'email';
        }

        return $channel['data_type'];
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
