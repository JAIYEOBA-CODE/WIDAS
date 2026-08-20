<?php

namespace Database\Factories;

use App\Models\IntrusionEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlertFactory extends Factory
{
    public function definition(): array
    {
        $types = ['threat', 'system', 'security', 'info'];
        $severities = ['low', 'medium', 'high', 'critical'];

        return [
            'user_id' => User::factory(),
            'intrusion_event_id' => IntrusionEvent::factory(),
            'type' => fake()->randomElement($types),
            'severity' => fake()->randomElement($severities),
            'title' => fake()->sentence(),
            'message' => fake()->paragraph(),
            'metadata' => ['source' => 'detection_engine'],
            'is_read' => false,
            'is_resolved' => false,
        ];
    }

    public function critical(): static
    {
        return $this->state(fn(array $attributes) => [
            'severity' => 'critical',
            'type' => 'security',
        ]);
    }
}
