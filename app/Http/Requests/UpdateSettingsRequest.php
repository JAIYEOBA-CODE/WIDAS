<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('manage-settings');
    }

    public function rules(): array
    {
        return [
            'value' => ['required', 'string'],
        ];
    }
}
