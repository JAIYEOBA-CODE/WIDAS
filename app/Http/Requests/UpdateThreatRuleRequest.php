<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateThreatRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('manage-threats');
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'category' => ['sometimes', 'string', 'in:brute_force,sql_injection,xss,session_abuse,unauthorized_access,api_abuse'],
            'severity' => ['sometimes', 'string', 'in:low,medium,high,critical'],
            'threat_score' => ['sometimes', 'integer', 'min:0', 'max:100'],
            'patterns' => ['sometimes', 'array'],
            'config' => ['sometimes', 'array'],
            'is_active' => ['sometimes', 'boolean'],
            'auto_block' => ['sometimes', 'boolean'],
            'threshold' => ['sometimes', 'integer', 'min:1'],
            'action' => ['sometimes', 'string', 'in:log,alert,block'],
        ];
    }
}
