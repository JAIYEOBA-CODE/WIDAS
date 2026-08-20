<?php

namespace App\Jobs;

use App\Models\ActivityLog;
use App\Models\AuditLog;
use App\Models\IntrusionEvent;
use App\Models\LoginAttempt;
use App\Models\SecurityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CleanupOldLogs implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $cutoff = now()->subDays(90);

        IntrusionEvent::where('created_at', '<', $cutoff)->delete();
        SecurityLog::where('created_at', '<', $cutoff)->delete();
        LoginAttempt::where('created_at', '<', $cutoff)->delete();
        ActivityLog::where('created_at', '<', $cutoff)->delete();
        AuditLog::where('created_at', '<', $cutoff)->delete();
    }
}
