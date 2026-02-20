<?php

namespace App\Enums;

enum NotificationType: string
{
    case EMAIL = 'email';
    case SMS = 'sms';
    case PUSH = 'push';
    case WEBHOOK = 'webhook';

    public function label(): string
    {
        return match ($this) {
            self::EMAIL => 'Email',
            self::SMS => 'SMS',
            self::PUSH => 'Push Notification',
            self::WEBHOOK => 'Webhook',
        };
    }
}
