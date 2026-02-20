<?php

namespace App\Enums;

enum NotificationStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SENT = 'sent';
    case FAILED = 'failed';
    case RETRYING = 'retrying';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PROCESSING => 'Processing',
            self::SENT => 'Sent',
            self::FAILED => 'Failed',
            self::RETRYING => 'Retrying',
        };
    }
}
