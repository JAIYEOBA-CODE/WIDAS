<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResolveIntrusionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('manage-threats');
    }

    public function rules(): array
    {
        return [
            'resolution_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
