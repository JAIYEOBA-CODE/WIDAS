<?php

namespace App\Events;

use App\Models\IntrusionEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IntrusionDetected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public IntrusionEvent $event
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('intrusions'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'intrusion.detected';
    }
}
