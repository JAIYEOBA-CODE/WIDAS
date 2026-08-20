<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlockIpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('manage-blocked-ips');
    }

    public function rules(): array
    {
        return [
            'ip_address' => ['required', 'ip'],
            'reason' => ['nullable', 'string', 'max:500'],
            'is_permanent' => ['sometimes', 'boolean'],
            'expires_at' => ['nullable', 'date', 'after:now'],
        ];
    }
}
