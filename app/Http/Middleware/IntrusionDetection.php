<?php

namespace App\Http\Middleware;

use App\Services\DetectionEngine;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class IntrusionDetection
{
    public function __construct(
        private DetectionEngine $detectionEngine
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Schema::hasTable('threat_rules')) {
            $this->detectionEngine->analyze($request);
        }

        return $response;
    }
}
