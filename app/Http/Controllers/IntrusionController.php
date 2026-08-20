<?php

namespace App\Http\Controllers;

use App\Models\IntrusionEvent;
use App\Http\Requests\ResolveIntrusionRequest;

class IntrusionController extends Controller
{
    public function index()
    {
        $events = IntrusionEvent::with(['user', 'threatRule'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return response()->json($events);
    }

    public function show(IntrusionEvent $event)
    {
        return response()->json($event->load(['user', 'threatRule', 'resolver', 'alerts']));
    }

    public function resolve(IntrusionEvent $event, ResolveIntrusionRequest $request)
    {
        $event->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => auth()->id(),
            'resolution_notes' => $request->input('resolution_notes'),
        ]);

        return back()->with('success', 'Threat resolved successfully.');
    }
}
