<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\IntrusionEvent;
use App\Services\ThreatAnalysisService;
use Inertia\Inertia;

class AnalystController extends Controller
{
    public function __construct(
        private ThreatAnalysisService $threatAnalysis
    ) {}

    public function dashboard()
    {
        return Inertia::render('Analyst/Dashboard', [
            'stats' => [
                'total_incidents' => IntrusionEvent::where('is_resolved', false)->count(),
                'critical_incidents' => IntrusionEvent::unresolved()->bySeverity('critical')->count(),
                'pending_alerts' => Alert::unread()->count(),
                'resolved_today' => IntrusionEvent::whereDate('resolved_at', today())->count(),
            ],
            'incidentQueue' => IntrusionEvent::unresolved()
                ->with(['user', 'threatRule'])
                ->orderBy('threat_score', 'desc')
                ->limit(20)
                ->get(),
            'recentAlerts' => Alert::with('intrusionEvent')
                ->unread()
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get(),
            'threatTrend' => $this->threatAnalysis->getThreatTrend(),
            'threatTypes' => $this->threatAnalysis->getThreatDistribution(),
            'overallThreatScore' => $this->threatAnalysis->calculateOverallThreatScore(),
        ]);
    }

    public function incidents()
    {
        $incidents = IntrusionEvent::with(['user', 'threatRule'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return Inertia::render('Analyst/Incidents', ['incidents' => $incidents]);
    }

    public function reviewAlerts()
    {
        $alerts = Alert::with(['user', 'intrusionEvent'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return Inertia::render('Analyst/Alerts', ['alerts' => $alerts]);
    }
}
