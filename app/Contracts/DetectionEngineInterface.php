<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface DetectionEngineInterface
{
    public function analyze(Request $request): void;
    public function detectBruteForce(Request $request): void;
    public function detectSqlInjection(Request $request): void;
    public function detectXss(Request $request): void;
    public function calculateThreatScore(string $type, string $severity): int;
}
