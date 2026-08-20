<?php

namespace App\Services;

use App\Models\IntrusionEvent;
use App\Models\ThreatScore;
use Illuminate\Support\Facades\DB;

class ThreatAnalysisService
{
    public function calculateOverallThreatScore(): int
    {
        $recentEvents = IntrusionEvent::where('created_at', '>=', now()->subDay())->get();

        if ($recentEvents->isEmpty()) return 0;

        $totalScore = $recentEvents->sum(function ($event) {
            $severityMultiplier = match ($event->severity) {
                'critical' => 1.0,
                'high' => 0.75,
                'medium' => 0.5,
                'low' => 0.25,
                default => 0.1,
            };
            return $event->threat_score * $severityMultiplier;
        });

        return min(100, (int)($totalScore / $recentEvents->count()));
    }

    public function getThreatDistribution(): array
    {
        return IntrusionEvent::select('type', DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }

    public function getSeverityDistribution(): array
    {
        return IntrusionEvent::select('severity', DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('severity')
            ->pluck('count', 'severity')
            ->toArray();
    }

    public function getThreatTrend(int $days = 7): array
    {
        $trend = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = IntrusionEvent::whereDate('created_at', $date)->count();
            $trend[] = ['date' => $date, 'count' => $count];
        }
        return $trend;
    }

    public function getLoginActivityTrend(int $days = 7): array
    {
        $trend = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $successful = \App\Models\LoginAttempt::whereDate('created_at', $date)->where('was_successful', true)->count();
            $failed = \App\Models\LoginAttempt::whereDate('created_at', $date)->where('was_successful', false)->count();
            $trend[] = ['date' => $date, 'successful' => $successful, 'failed' => $failed];
        }
        return $trend;
    }

    public function updateThreatScore(string $ip, int $score): void
    {
        $riskLevel = match (true) {
            $score >= 80 => 'critical',
            $score >= 60 => 'high',
            $score >= 40 => 'medium',
            $score >= 20 => 'low',
            default => 'safe',
        };

        ThreatScore::updateOrCreate(
            ['source_ip' => $ip],
            [
                'score' => $score,
                'risk_level' => $riskLevel,
                'last_updated_at' => now(),
            ]
        );
    }
}
