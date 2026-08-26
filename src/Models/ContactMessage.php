<?php

namespace Azuriom\Plugin\ReachUs\Models;

use Azuriom\Models\Traits\HasTablePrefix;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasTablePrefix;

    public const METHOD_WHATSAPP = 'whatsapp';
    public const METHOD_TELEGRAM = 'telegram';
    public const METHOD_EMAIL = 'email';
    public const METHOD_DISCORD = 'discord';

    protected string $prefix = 'reachus_';

    protected $fillable = [
        'name', 'contact_method', 'contact_value', 'reason', 'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public static function contactMethods(): array
    {
        return [
            self::METHOD_WHATSAPP,
            self::METHOD_TELEGRAM,
            self::METHOD_EMAIL,
            self::METHOD_DISCORD,
        ];
    }
}
