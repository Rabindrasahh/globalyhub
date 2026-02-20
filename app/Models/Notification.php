<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\NotificationStatus;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'notification_template_id',
        'recipients',
        'payload',
        'status',
        'error_message',
        'error_trace',
        'attempts',
        'max_attempts',
        'scheduled_at',
        'sent_at',
        'failed_at'
    ];

    protected $casts = [
        'recipients' => 'array',
        'payload' => 'array',
        'error_trace' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'failed_at' => 'datetime',
        'status' => NotificationStatus::class,
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(NotificationLog::class);
    }

    public function template()
    {
        return $this->belongsTo(NotificationTemplate::class, 'notification_template_id');
    }

    public function isPending(): bool
    {
        return $this->status === NotificationStatus::PENDING;
    }

    public function isSent(): bool
    {
        return $this->status === NotificationStatus::SENT;
    }

    public function hasFailed(): bool
    {
        return $this->status === NotificationStatus::FAILED;
    }

    public function canRetry(): bool
    {
        return $this->attempts < $this->max_attempts;
    }

    public function scopePending($query)
    {
        return $query->where('status', NotificationStatus::PENDING->value);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', NotificationStatus::FAILED->value);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeInTimeRange($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }
}
