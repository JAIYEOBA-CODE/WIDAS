<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAlertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('manage-alerts');
    }

    public function rules(): array
    {
        return [
            'is_read' => ['sometimes', 'boolean'],
            'is_resolved' => ['sometimes', 'boolean'],
        ];
    }
}
