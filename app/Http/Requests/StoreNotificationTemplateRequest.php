<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\NotificationType;

class StoreNotificationTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:notification_templates,name'
            ],

            'type' => ['required', Rule::enum(NotificationType::class)],

            'subject' => [
                'nullable',
                'string',
                'max:255'
            ],

            'body' => [
                'required',
                'string'
            ],

            'variables' => [
                'nullable',
                'array'
            ],

            'variables.*' => [
                'string'
            ],

            'metadata' => [
                'nullable',
                'array'
            ],

            'is_active' => [
                'sometimes',
                'boolean'
            ],
        ];
    }
}
