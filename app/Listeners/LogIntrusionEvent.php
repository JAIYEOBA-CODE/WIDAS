<?php

namespace App\Listeners;

use App\Events\IntrusionDetected;
use App\Models\SecurityLog;
use Illuminate\Support\Facades\Log;

class LogIntrusionEvent
{
    public function handle(IntrusionDetected $event): void
    {
        SecurityLog::create([
            'user_id' => $event->event->user_id,
            'type' => $event->event->type,
            'severity' => $event->event->severity,
            'source_ip' => $event->event->source_ip,
            'user_agent' => $event->event->user_agent,
            'metadata' => [
                'threat_score' => $event->event->threat_score,
                'url' => $event->event->url,
                'method' => $event->event->method,
            ],
            'message' => "Intrusion event detected — Type: {$event->event->type}, Severity: {$event->event->severity}, Threat Score: {$event->event->threat_score}, Source: {$event->event->source_ip}",
        ]);

        Log::warning('Intrusion detected', [
            'type' => $event->event->type,
            'severity' => $event->event->severity,
            'ip' => $event->event->source_ip,
            'score' => $event->event->threat_score,
        ]);
    }
}
