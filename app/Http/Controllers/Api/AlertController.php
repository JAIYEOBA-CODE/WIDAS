<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use Illuminate\Http\JsonResponse;

class AlertController extends Controller
{
    public function index(): JsonResponse
    {
        $alerts = Alert::with('intrusionEvent')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($alerts);
    }

    public function show(Alert $alert): JsonResponse
    {
        return response()->json($alert->load('intrusionEvent'));
    }

    public function unreadCount(): JsonResponse
    {
        return response()->json([
            'total' => Alert::unread()->count(),
            'critical' => Alert::unread()->bySeverity('critical')->count(),
        ]);
    }

    public function markAsRead(Alert $alert): JsonResponse
    {
        $alert->markAsRead();
        return response()->json(['message' => 'Alert marked as read']);
    }
}
