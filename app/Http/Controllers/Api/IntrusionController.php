<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IntrusionEvent;
use Illuminate\Http\JsonResponse;

class IntrusionController extends Controller
{
    public function index(): JsonResponse
    {
        $events = IntrusionEvent::with(['threatRule'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($events);
    }

    public function show(IntrusionEvent $event): JsonResponse
    {
        return response()->json($event->load(['threatRule', 'alerts']));
    }

    public function stats(): JsonResponse
    {
        return response()->json([
            'total' => IntrusionEvent::count(),
            'unresolved' => IntrusionEvent::unresolved()->count(),
            'by_severity' => [
                'critical' => IntrusionEvent::bySeverity('critical')->count(),
                'high' => IntrusionEvent::bySeverity('high')->count(),
                'medium' => IntrusionEvent::bySeverity('medium')->count(),
                'low' => IntrusionEvent::bySeverity('low')->count(),
            ],
        ]);
    }
}
