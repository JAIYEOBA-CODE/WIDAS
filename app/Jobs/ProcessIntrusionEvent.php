<?php

namespace App\Jobs;

use App\Models\IntrusionEvent;
use App\Services\AlertService;
use App\Services\ThreatAnalysisService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessIntrusionEvent implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public IntrusionEvent $event
    ) {}

    public function handle(AlertService $alertService, ThreatAnalysisService $threatAnalysis): void
    {
        $alert = $alertService->createAlert($this->event);

        $threatAnalysis->updateThreatScore(
            $this->event->source_ip,
            $this->event->threat_score
        );
    }
}
