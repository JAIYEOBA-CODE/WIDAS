<?php

namespace Tests\Unit;

use App\Models\IntrusionEvent;
use App\Services\ThreatAnalysisService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreatAnalysisServiceTest extends TestCase
{
    use RefreshDatabase;

    private ThreatAnalysisService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ThreatAnalysisService::class);
    }

    public function test_calculates_overall_threat_score(): void
    {
        IntrusionEvent::factory()->count(5)->create([
            'threat_score' => 80,
            'severity' => 'critical',
        ]);

        $score = $this->service->calculateOverallThreatScore();

        $this->assertGreaterThan(0, $score);
        $this->assertLessThanOrEqual(100, $score);
    }

    public function test_returns_zero_when_no_events(): void
    {
        $score = $this->service->calculateOverallThreatScore();
        $this->assertEquals(0, $score);
    }

    public function test_gets_threat_distribution(): void
    {
        IntrusionEvent::factory()->create(['type' => 'brute_force']);
        IntrusionEvent::factory()->create(['type' => 'sql_injection']);
        IntrusionEvent::factory()->create(['type' => 'sql_injection']);

        $distribution = $this->service->getThreatDistribution();

        $this->assertArrayHasKey('brute_force', $distribution);
        $this->assertArrayHasKey('sql_injection', $distribution);
        $this->assertEquals(1, $distribution['brute_force']);
        $this->assertEquals(2, $distribution['sql_injection']);
    }

    public function test_gets_threat_trend(): void
    {
        IntrusionEvent::factory()->create();

        $trend = $this->service->getThreatTrend(7);

        $this->assertCount(7, $trend);
        $this->assertArrayHasKey('date', $trend[0]);
        $this->assertArrayHasKey('count', $trend[0]);
    }
}
