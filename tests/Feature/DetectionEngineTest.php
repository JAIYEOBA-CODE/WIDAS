<?php

namespace Tests\Feature;

use App\Models\IntrusionEvent;
use App\Models\ThreatRule;
use App\Services\DetectionEngine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class DetectionEngineTest extends TestCase
{
    use RefreshDatabase;

    private DetectionEngine $engine;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->engine = app(DetectionEngine::class);
    }

    public function test_detects_sql_injection(): void
    {
        $request = Request::create('/test', 'GET', ['search' => "' OR '1'='1"]);

        $this->engine->analyze($request);

        $this->assertDatabaseHas('intrusion_events', ['type' => 'sql_injection']);
    }

    public function test_detects_xss_attempt(): void
    {
        $request = Request::create('/test', 'GET', ['comment' => '<script>alert("xss")</script>']);

        $this->engine->analyze($request);

        $this->assertDatabaseHas('intrusion_events', ['type' => 'xss']);
    }

    public function test_detects_multiple_threats(): void
    {
        $request = Request::create('/test', 'GET', [
            'search' => "1; DROP TABLE users",
            'name' => '<script>alert(1)</script>',
        ]);

        $this->engine->analyze($request);

        $this->assertDatabaseHas('intrusion_events', ['type' => 'sql_injection']);
        $this->assertDatabaseHas('intrusion_events', ['type' => 'xss']);
    }

    public function test_clean_request_passes_through(): void
    {
        $request = Request::create('/test', 'GET', ['search' => 'normal query']);

        $this->engine->analyze($request);

        $this->assertEquals(0, IntrusionEvent::count());
    }

    public function test_threat_score_calculation(): void
    {
        $score = $this->engine->calculateThreatScore('sql_injection', 'critical');
        $this->assertEquals(90, $score);

        $score = $this->engine->calculateThreatScore('brute_force', 'low');
        $this->assertEquals(19, $score);
    }
}
