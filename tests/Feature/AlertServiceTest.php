<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\IntrusionEvent;
use App\Models\User;
use App\Services\AlertService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlertServiceTest extends TestCase
{
    use RefreshDatabase;

    private AlertService $alertService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->alertService = app(AlertService::class);
    }

    public function test_creates_alert_from_intrusion_event(): void
    {
        $event = IntrusionEvent::factory()->create();

        $alert = $this->alertService->createAlert($event);

        $this->assertDatabaseHas('alerts', [
            'id' => $alert->id,
            'intrusion_event_id' => $event->id,
        ]);
    }

    public function test_resolves_alert(): void
    {
        $alert = Alert::factory()->create();

        $this->alertService->resolveAlert($alert, 'Investigated and resolved');

        $this->assertDatabaseHas('alerts', [
            'id' => $alert->id,
            'is_resolved' => true,
        ]);
    }

    public function test_gets_unresolved_alerts(): void
    {
        Alert::factory()->count(3)->create(['is_resolved' => false]);
        Alert::factory()->create(['is_resolved' => true]);

        $unresolved = $this->alertService->getUnresolvedAlerts();

        $this->assertCount(3, $unresolved);
    }
}
