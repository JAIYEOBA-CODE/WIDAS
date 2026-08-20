<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name', 'value' => 'WIDAS', 'group' => 'general', 'description' => 'Application name'],
            ['key' => 'app_description', 'value' => 'Web-Based Intrusion Detection and Alert System', 'group' => 'general', 'description' => 'Application description'],
            ['key' => 'login_threshold', 'value' => '5', 'group' => 'security', 'description' => 'Max failed login attempts before lockout'],
            ['key' => 'lockout_duration', 'value' => '30', 'group' => 'security', 'description' => 'Account lockout duration in minutes'],
            ['key' => 'brute_force_window', 'value' => '15', 'group' => 'security', 'description' => 'Time window for brute force detection in minutes'],
            ['key' => 'rate_limit_api', 'value' => '60', 'group' => 'security', 'description' => 'API rate limit per minute'],
            ['key' => 'rate_limit_login', 'value' => '5', 'group' => 'security', 'description' => 'Login rate limit per minute'],
            ['key' => 'enable_sql_injection_detection', 'value' => 'true', 'group' => 'detection', 'description' => 'Enable SQL injection detection'],
            ['key' => 'enable_xss_detection', 'value' => 'true', 'group' => 'detection', 'description' => 'Enable XSS detection'],
            ['key' => 'enable_brute_force_detection', 'value' => 'true', 'group' => 'detection', 'description' => 'Enable brute force detection'],
            ['key' => 'auto_block_enabled', 'value' => 'true', 'group' => 'security', 'description' => 'Enable automatic IP blocking'],
            ['key' => 'alert_notification_enabled', 'value' => 'true', 'group' => 'notifications', 'description' => 'Enable alert notifications'],
            ['key' => 'email_notifications_enabled', 'value' => 'true', 'group' => 'notifications', 'description' => 'Enable email notifications'],
            ['key' => 'retention_days', 'value' => '90', 'group' => 'general', 'description' => 'Data retention period in days'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
