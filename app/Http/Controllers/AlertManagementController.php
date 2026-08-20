<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Http\Requests\UpdateAlertRequest;
use Illuminate\Http\Request;

class AlertManagementController extends Controller
{
    public function index()
    {
        $alerts = Alert::with(['user', 'intrusionEvent'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return response()->json($alerts);
    }

    public function show(Alert $alert)
    {
        return response()->json($alert->load(['user', 'intrusionEvent']));
    }

    public function update(Alert $alert, UpdateAlertRequest $request)
    {
        if ($request->boolean('is_read')) {
            $alert->markAsRead();
        }

        if ($request->boolean('is_resolved')) {
            $alert->markAsResolved();
        }

        return back()->with('success', 'Alert updated successfully.');
    }

    public function markAsRead(Alert $alert)
    {
        $alert->markAsRead();
        return back()->with('success', 'Alert marked as read.');
    }

    public function resolve(Alert $alert)
    {
        $alert->markAsResolved();
        return back()->with('success', 'Alert resolved successfully.');
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => Alert::unread()->count(),
            'critical' => Alert::unread()->bySeverity('critical')->count(),
        ]);
    }
}
