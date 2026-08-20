<?php

namespace App\Contracts;

use App\Models\Alert;
use App\Models\IntrusionEvent;

interface AlertServiceInterface
{
    public function createAlert(IntrusionEvent $event): Alert;
    public function sendAlert(Alert $alert): void;
    public function resolveAlert(Alert $alert, string $notes = null): void;
    public function getUnresolvedAlerts();
    public function getAlertsBySeverity(string $severity);
}
