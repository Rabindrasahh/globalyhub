<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\NotificationStatus;

class StoreNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'tenant_id' => ['nullable', 'integer'],
            'notification_template_id' => 'required|exists:notification_templates,id',
            'recipients' => ['required', 'array'],
            'recipients.*' => ['required'],
            'payload' => ['required', 'array'],
            'status' => [
                'sometimes',
                'string',
                Rule::enum(NotificationStatus::class)
            ],
            'attempts' => ['sometimes', 'integer', 'min:0'],
            'max_attempts' => ['sometimes', 'integer', 'min:1'],
            'scheduled_at' => ['nullable', 'date'],
            'sent_at' => ['nullable', 'date'],
            'failed_at' => ['nullable', 'date'],
        ];
    }
}
