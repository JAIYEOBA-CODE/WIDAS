<?php

namespace App\Http\Controllers;

use App\Models\BlockedIp;
use App\Http\Requests\BlockIpRequest;
use App\Models\SecurityLog;

class IpManagementController extends Controller
{
    public function index()
    {
        $blockedIps = BlockedIp::with('blocker')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return response()->json($blockedIps);
    }

    public function store(BlockIpRequest $request)
    {
        $blocked = BlockedIp::create([
            'ip_address' => $request->input('ip_address'),
            'reason' => $request->input('reason'),
            'blocked_by' => auth()->id(),
            'is_permanent' => $request->boolean('is_permanent', false),
            'expires_at' => $request->input('expires_at'),
        ]);

        $blockReason = $request->input('reason');
        SecurityLog::create([
            'type' => 'block',
            'severity' => 'warning',
            'source_ip' => $request->input('ip_address'),
            'message' => "IP address {$request->input('ip_address')} manually blocked by " . auth()->user()->email . ($blockReason ? " — Reason: {$blockReason}" : ""),
        ]);

        return back()->with('success', 'IP blocked successfully.');
    }

    public function unblock(BlockedIp $blockedIp)
    {
        SecurityLog::create([
            'type' => 'unblock',
            'severity' => 'info',
            'source_ip' => $blockedIp->ip_address,
            'message' => "IP address {$blockedIp->ip_address} reinstated by " . auth()->user()->email . " — previously blocked for: {$blockedIp->reason}",
        ]);

        $blockedIp->delete();

        return back()->with('success', 'IP unblocked successfully.');
    }
}
