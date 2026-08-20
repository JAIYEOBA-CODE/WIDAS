<?php

namespace Database\Seeders;

use App\Models\ThreatRule;
use Illuminate\Database\Seeder;

class ThreatRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'name' => 'Brute Force Login Detection',
                'slug' => 'brute-force-login-detection',
                'description' => 'Detects multiple failed login attempts from the same IP address within a short time window',
                'category' => 'brute_force',
                'severity' => 'high',
                'threat_score' => 75,
                'patterns' => ['failed_login', 'multiple_attempts', 'rapid_requests'],
                'config' => ['time_window' => 15, 'max_attempts' => 5, 'lockout_duration' => 30],
                'is_active' => true,
                'auto_block' => true,
                'threshold' => 5,
                'action' => 'block',
            ],
            [
                'name' => 'SQL Injection Pattern Detection',
                'slug' => 'sql-injection-pattern-detection',
                'description' => 'Detects SQL injection attempts by matching common SQL injection patterns in request data',
                'category' => 'sql_injection',
                'severity' => 'critical',
                'threat_score' => 90,
                'patterns' => [
                    "union.*select", "or.*1=1", "drop.*table", "information_schema",
                    "select.*from", "insert.*into", "delete.*from", "update.*set",
                    "exec.*sp_", "declare.*@", "waitfor.*delay", "benchmark",
                    "or.*true", "or.*false", "'\\s*or\\s*'", "1=1", "1=2",
                    "';\\s*--", "';\\s*#", "';\\s*drop", "';\\s*exec",
                ],
                'config' => ['check_get' => true, 'check_post' => true, 'check_cookies' => true, 'check_headers' => true],
                'is_active' => true,
                'auto_block' => true,
                'threshold' => 1,
                'action' => 'block',
            ],
            [
                'name' => 'Cross-Site Scripting Detection',
                'slug' => 'cross-site-scripting-detection',
                'description' => 'Detects XSS attack attempts by matching common XSS patterns',
                'category' => 'xss',
                'severity' => 'high',
                'threat_score' => 85,
                'patterns' => [
                    "<script", "javascript:", "onerror=", "onclick=", "onload=",
                    "onmouseover=", "onfocus=", "onchange=", "onkeypress=",
                    "alert\(", "document\.cookie", "document\.location",
                    "<iframe", "<embed", "<object", "<svg", "prompt\(",
                    "fromCharCode", "eval\(", "expression\(",
                ],
                'config' => ['check_get' => true, 'check_post' => true, 'check_cookies' => true, 'check_headers' => true],
                'is_active' => true,
                'auto_block' => false,
                'threshold' => 1,
                'action' => 'alert',
            ],
            [
                'name' => 'Session Abuse Detection',
                'slug' => 'session-abuse-detection',
                'description' => 'Detects abnormal session behavior including session hijacking and fixation',
                'category' => 'session_abuse',
                'severity' => 'high',
                'threat_score' => 70,
                'patterns' => ['multiple_sessions', 'session_fixation', 'session_hijacking', 'rapid_navigation'],
                'config' => ['max_sessions_per_user' => 3, 'time_window' => 30],
                'is_active' => true,
                'auto_block' => false,
                'threshold' => 3,
                'action' => 'alert',
            ],
            [
                'name' => 'Unauthorized Access Detection',
                'slug' => 'unauthorized-access-detection',
                'description' => 'Detects attempts to access restricted resources or perform unauthorized actions',
                'category' => 'unauthorized_access',
                'severity' => 'critical',
                'threat_score' => 95,
                'patterns' => ['unauthorized_access', 'resource_bypass', 'privilege_escalation', 'direct_file_access'],
                'config' => ['log_all_attempts' => true, 'auto_notify_admin' => true],
                'is_active' => true,
                'auto_block' => true,
                'threshold' => 1,
                'action' => 'block',
            ],
            [
                'name' => 'API Abuse Detection',
                'slug' => 'api-abuse-detection',
                'description' => 'Detects API abuse including excessive requests and malformed payloads',
                'category' => 'api_abuse',
                'severity' => 'medium',
                'threat_score' => 60,
                'patterns' => ['excessive_requests', 'malformed_payload', 'rate_limit_exceeded', 'invalid_tokens'],
                'config' => ['max_requests_per_minute' => 60, 'max_requests_per_hour' => 1000],
                'is_active' => true,
                'auto_block' => true,
                'threshold' => 60,
                'action' => 'block',
            ],
        ];

        foreach ($rules as $rule) {
            ThreatRule::firstOrCreate(['slug' => $rule['slug']], $rule);
        }
    }
}
