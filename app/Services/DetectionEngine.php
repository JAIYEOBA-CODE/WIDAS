<?php

namespace App\Services;

use App\Contracts\DetectionEngineInterface;
use App\Models\BlockedIp;
use App\Models\IntrusionEvent;
use App\Models\LoginAttempt;
use App\Models\SecurityLog;
use App\Models\ThreatRule;
use App\Models\ThreatScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DetectionEngine implements DetectionEngineInterface
{
    private array $detectedThreats = [];

    public function analyze(Request $request): void
    {
        $this->detectBruteForce($request);
        $this->detectSqlInjection($request);
        $this->detectXss($request);

        if (!empty($this->detectedThreats)) {
            $this->processThreats($request);
        }
    }

    public function detectBruteForce(Request $request): void
    {
        if ($request->is('login') && $request->isMethod('post')) {
            $ip = $request->ip();
            $email = $request->input('email');

            $recentAttempts = LoginAttempt::fromIp($ip)
                ->failed()
                ->recent(15)
                ->count();

            $rule = ThreatRule::where('slug', 'brute-force-login-detection')->first();

            if ($rule && $recentAttempts >= $rule->threshold) {
                $this->detectedThreats[] = [
                    'type' => 'brute_force',
                    'rule' => $rule,
                    'score' => $rule->threat_score,
                    'severity' => $recentAttempts >= $rule->threshold * 2 ? 'critical' : $rule->severity,
                    'description' => "Brute force attack detected from IP {$ip}. {$recentAttempts} failed attempts in 15 minutes.",
                ];
            }
        }
    }

    public function detectSqlInjection(Request $request): void
    {
        $rule = ThreatRule::where('slug', 'sql-injection-pattern-detection')->where('is_active', true)->first();
        if (!$rule) return;

        $patterns = $rule->patterns ?? [];
        $data = $this->getRequestData($request);

        foreach ($data as $key => $value) {
            if (!is_string($value)) continue;

            foreach ($patterns as $pattern) {
                if (preg_match("/{$pattern}/i", $value)) {
                    $this->detectedThreats[] = [
                        'type' => 'sql_injection',
                        'rule' => $rule,
                        'score' => $rule->threat_score,
                        'severity' => $rule->severity,
                        'description' => "SQL Injection attempt detected in field '{$key}' with pattern '{$pattern}'",
                        'payload' => [$key => $value],
                    ];
                    break;
                }
            }
        }
    }

    public function detectXss(Request $request): void
    {
        $rule = ThreatRule::where('slug', 'cross-site-scripting-detection')->where('is_active', true)->first();
        if (!$rule) return;

        $patterns = $rule->patterns ?? [];
        $data = $this->getRequestData($request);

        foreach ($data as $key => $value) {
            if (!is_string($value)) continue;

            foreach ($patterns as $pattern) {
                if (preg_match("/{$pattern}/i", $value)) {
                    $this->detectedThreats[] = [
                        'type' => 'xss',
                        'rule' => $rule,
                        'score' => $rule->threat_score,
                        'severity' => $rule->severity,
                        'description' => "XSS attempt detected in field '{$key}' with pattern '{$pattern}'",
                        'payload' => [$key => $value],
                    ];
                    break;
                }
            }
        }
    }

    public function calculateThreatScore(string $type, string $severity): int
    {
        $baseScores = [
            'brute_force' => 75,
            'sql_injection' => 90,
            'xss' => 85,
            'session_abuse' => 70,
            'unauthorized_access' => 95,
            'api_abuse' => 60,
        ];

        $severityMultipliers = [
            'low' => 0.25,
            'medium' => 0.5,
            'high' => 0.75,
            'critical' => 1.0,
        ];

        $baseScore = $baseScores[$type] ?? 50;
        $multiplier = $severityMultipliers[$severity] ?? 0.5;

        return min(100, (int)round($baseScore * $multiplier));
    }

    private function getRequestData(Request $request): array
    {
        return array_merge(
            $request->query->all(),
            $request->request->all(),
            $request->cookies->all()
        );
    }

    private function processThreats(Request $request): void
    {
        foreach ($this->detectedThreats as $threat) {
            $rule = $threat['rule'];

            $event = IntrusionEvent::create([
                'threat_rule_id' => $rule->id,
                'type' => $threat['type'],
                'severity' => $threat['severity'],
                'threat_score' => $threat['score'],
                'source_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'payload' => $threat['payload'] ?? null,
                'headers' => $request->headers->all(),
                'description' => $threat['description'],
            ]);

            SecurityLog::create([
                'type' => $threat['type'],
                'severity' => $threat['severity'],
                'source_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'metadata' => $threat,
                'message' => $threat['description'],
            ]);

            if ($rule->auto_block && $rule->action === 'block') {
                BlockedIp::firstOrCreate(
                    ['ip_address' => $request->ip()],
                    [
                        'reason' => "Auto-blocked: {$threat['description']}",
                        'is_permanent' => false,
                        'expires_at' => now()->addMinutes(3),
                        'attempts' => 1,
                    ]
                );
            }

            try {
                $event->alerts()->create([
                    'type' => 'security',
                    'severity' => $threat['severity'],
                    'title' => ucfirst(str_replace('_', ' ', $threat['type'])) . ' Detected',
                    'message' => $threat['description'],
                    'metadata' => ['threat' => $threat],
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create alert: ' . $e->getMessage());
            }
        }
    }
}
