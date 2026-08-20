<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\BlockedIp;
use App\Models\IntrusionEvent;
use App\Models\LoginAttempt;
use App\Models\User;

class ReportingService
{
    public function generateDailyReport(): array
    {
        return [
            'period' => 'daily',
            'date' => now()->format('Y-m-d'),
            'summary' => [
                'total_threats' => IntrusionEvent::whereDate('created_at', today())->count(),
                'critical_alerts' => Alert::whereDate('created_at', today())->bySeverity('critical')->count(),
                'blocked_ips' => BlockedIp::whereDate('created_at', today())->count(),
                'failed_logins' => LoginAttempt::whereDate('created_at', today())->failed()->count(),
                'active_users' => User::active()->count(),
            ],
            'threats_by_type' => IntrusionEvent::whereDate('created_at', today())
                ->selectRaw('type, count(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
            'threats_by_severity' => IntrusionEvent::whereDate('created_at', today())
                ->selectRaw('severity, count(*) as count')
                ->groupBy('severity')
                ->pluck('count', 'severity')
                ->toArray(),
            'top_ips' => IntrusionEvent::whereDate('created_at', today())
                ->selectRaw('source_ip, count(*) as count')
                ->groupBy('source_ip')
                ->orderByRaw('count(*) desc')
                ->limit(5)
                ->pluck('count', 'source_ip')
                ->toArray(),
        ];
    }

    public function generateWeeklyReport(): array
    {
        return [
            'period' => 'weekly',
            'start_date' => now()->startOfWeek()->format('Y-m-d'),
            'end_date' => now()->endOfWeek()->format('Y-m-d'),
            'summary' => [
                'total_threats' => IntrusionEvent::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'critical_alerts' => Alert::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->bySeverity('critical')->count(),
                'blocked_ips' => BlockedIp::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'failed_logins' => LoginAttempt::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->failed()->count(),
                'new_users' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ],
            'daily_breakdown' => $this->getDailyBreakdown('week'),
            'threats_by_type' => $this->getThreatsByType('week'),
        ];
    }

    public function generateMonthlyReport(): array
    {
        return [
            'period' => 'monthly',
            'month' => now()->format('F Y'),
            'summary' => [
                'total_threats' => IntrusionEvent::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
                'critical_alerts' => Alert::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->bySeverity('critical')->count(),
                'blocked_ips' => BlockedIp::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
                'failed_logins' => LoginAttempt::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->failed()->count(),
                'new_users' => User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
                'total_resolved_threats' => IntrusionEvent::where('is_resolved', true)
                    ->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            ],
            'daily_breakdown' => $this->getDailyBreakdown('month'),
            'threats_by_type' => $this->getThreatsByType('month'),
            'severity_distribution' => $this->getSeverityDistribution('month'),
        ];
    }

    private function getDailyBreakdown(string $period): array
    {
        $start = $period === 'week' ? now()->startOfWeek() : now()->startOfMonth();
        $end = now();
        $breakdown = [];

        for ($date = $start; $date->lte($end); $date->addDay()) {
            $breakdown[] = [
                'date' => $date->format('Y-m-d'),
                'threats' => IntrusionEvent::whereDate('created_at', $date)->count(),
                'alerts' => Alert::whereDate('created_at', $date)->count(),
            ];
        }

        return $breakdown;
    }

    private function getThreatsByType(string $period): array
    {
        $start = $period === 'week' ? now()->startOfWeek() : now()->startOfMonth();

        return IntrusionEvent::where('created_at', '>=', $start)
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();
    }

    private function getSeverityDistribution(string $period): array
    {
        $start = $period === 'week' ? now()->startOfWeek() : now()->startOfMonth();

        return IntrusionEvent::where('created_at', '>=', $start)
            ->selectRaw('severity, count(*) as count')
            ->groupBy('severity')
            ->pluck('count', 'severity')
            ->toArray();
    }
}
