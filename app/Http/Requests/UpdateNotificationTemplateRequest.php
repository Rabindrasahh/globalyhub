<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\NotificationType;

class UpdateNotificationTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255'
            ],
            'type' => ['required', Rule::enum(NotificationType::class)],

            'subject' => ['nullable', 'string', 'max:255'],

            'body' => ['sometimes', 'string'],

            'variables' => ['nullable', 'array'],
            'variables.*' => ['string'],

            'metadata' => ['nullable', 'array'],

            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
