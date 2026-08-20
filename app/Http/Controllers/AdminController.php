<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\BlockedIp;
use App\Models\IntrusionEvent;
use App\Models\SecurityLog;
use App\Models\User;
use App\Services\ThreatAnalysisService;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function __construct(
        private ThreatAnalysisService $threatAnalysis
    ) {}

    public function dashboard()
    {
        $stats = [
            'total_threats' => IntrusionEvent::count(),
            'active_threats' => IntrusionEvent::unresolved()->count(),
            'critical_alerts' => Alert::unread()->bySeverity('critical')->count(),
            'blocked_ips' => BlockedIp::active()->count(),
            'active_users' => User::active()->count(),
            'total_alerts' => Alert::count(),
            'resolved_threats' => IntrusionEvent::where('is_resolved', true)->count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'threatTrend' => $this->threatAnalysis->getThreatTrend(),
            'threatTypes' => $this->threatAnalysis->getThreatDistribution(),
            'severityDistribution' => $this->threatAnalysis->getSeverityDistribution(),
            'loginActivity' => $this->threatAnalysis->getLoginActivityTrend(),
            'recentAlerts' => Alert::with('intrusionEvent')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'recentIntrusions' => IntrusionEvent::with(['user', 'threatRule'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'overallThreatScore' => $this->threatAnalysis->calculateOverallThreatScore(),
        ]);
    }

    public function users()
    {
        $users = User::with('role')->orderBy('created_at', 'desc')->paginate(15);
        return Inertia::render('Admin/Users', ['users' => $users]);
    }

    public function alerts()
    {
        $alerts = Alert::with(['user', 'intrusionEvent'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return Inertia::render('Admin/Alerts', ['alerts' => $alerts]);
    }

    public function threats()
    {
        $threats = IntrusionEvent::with(['user', 'threatRule'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return Inertia::render('Admin/Threats', ['threats' => $threats]);
    }

    public function blockedIps()
    {
        $blockedIps = BlockedIp::with('blocker')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return Inertia::render('Admin/BlockedIps', ['blockedIps' => $blockedIps]);
    }

    public function securityLogs()
    {
        $logs = SecurityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        return Inertia::render('Admin/SecurityLogs', ['logs' => $logs]);
    }

    public function activityLogs()
    {
        $logs = \App\Models\ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        return Inertia::render('Admin/ActivityLogs', ['logs' => $logs]);
    }

    public function auditLogs()
    {
        $logs = \App\Models\AuditLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(30);
        return Inertia::render('Admin/AuditLogs', ['logs' => $logs]);
    }

    public function settings()
    {
        $settings = \App\Models\SystemSetting::orderBy('group')->get()->groupBy('group');
        return Inertia::render('Admin/Settings', ['settings' => $settings]);
    }

    public function threatRules()
    {
        $rules = \App\Models\ThreatRule::orderBy('category')->paginate(15);
        return Inertia::render('Admin/ThreatRules', ['rules' => $rules]);
    }
}
