<?php

namespace App\Services;

use App\Contracts\AlertServiceInterface;
use App\Models\Alert;
use App\Models\IntrusionEvent;
use App\Models\User;
use App\Notifications\SecurityAlert;
use Illuminate\Support\Facades\Log;

class AlertService implements AlertServiceInterface
{
    public function createAlert(IntrusionEvent $event): Alert
    {
        $alert = Alert::create([
            'intrusion_event_id' => $event->id,
            'type' => 'security',
            'severity' => $event->severity,
            'title' => ucfirst(str_replace('_', ' ', $event->type)) . ' Detected',
            'message' => $event->description ?? "A {$event->severity} threat was detected from IP {$event->source_ip}",
            'metadata' => [
                'type' => $event->type,
                'source_ip' => $event->source_ip,
                'threat_score' => $event->threat_score,
            ],
        ]);

        $this->sendAlert($alert);

        return $alert;
    }

    public function sendAlert(Alert $alert): void
    {
        try {
            $admins = User::whereHas('role', function ($q) {
                $q->where('slug', 'admin');
            })->get();

            foreach ($admins as $admin) {
                $admin->notify(new SecurityAlert($alert));
            }

            if ($alert->severity === 'critical' || $alert->severity === 'high') {
                $analysts = User::whereHas('role', function ($q) {
                    $q->where('slug', 'analyst');
                })->get();

                foreach ($analysts as $analyst) {
                    $analyst->notify(new SecurityAlert($alert));
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send alert notification: ' . $e->getMessage());
        }
    }

    public function resolveAlert(Alert $alert, ?string $notes = null): void
    {
        $alert->update([
            'is_resolved' => true,
            'resolved_at' => now(),
        ]);
    }

    public function getUnresolvedAlerts()
    {
        return Alert::where('is_resolved', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getAlertsBySeverity(string $severity)
    {
        return Alert::bySeverity($severity)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
