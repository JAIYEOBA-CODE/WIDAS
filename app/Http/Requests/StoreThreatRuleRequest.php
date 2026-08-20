<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreThreatRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('create-threat-rules');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:threat_rules'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'in:brute_force,sql_injection,xss,session_abuse,unauthorized_access,api_abuse'],
            'severity' => ['required', 'string', 'in:low,medium,high,critical'],
            'threat_score' => ['required', 'integer', 'min:0', 'max:100'],
            'patterns' => ['sometimes', 'array'],
            'config' => ['sometimes', 'array'],
            'is_active' => ['sometimes', 'boolean'],
            'auto_block' => ['sometimes', 'boolean'],
            'threshold' => ['required', 'integer', 'min:1'],
            'action' => ['required', 'string', 'in:log,alert,block'],
        ];
    }
}
