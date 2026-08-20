<?php

namespace Database\Factories;

use App\Models\ThreatRule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IntrusionEventFactory extends Factory
{
    public function definition(): array
    {
        $types = ['brute_force', 'sql_injection', 'xss', 'session_abuse', 'unauthorized_access', 'api_abuse'];
        $severities = ['low', 'medium', 'high', 'critical'];

        return [
            'user_id' => User::factory(),
            'threat_rule_id' => ThreatRule::factory(),
            'type' => fake()->randomElement($types),
            'severity' => fake()->randomElement($severities),
            'threat_score' => fake()->numberBetween(10, 100),
            'source_ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'method' => fake()->randomElement(['GET', 'POST', 'PUT', 'DELETE']),
            'url' => fake()->url(),
            'payload' => ['data' => fake()->sentence()],
            'headers' => ['user-agent' => fake()->userAgent()],
            'description' => fake()->paragraph(),
            'is_resolved' => false,
        ];
    }

    public function resolved(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => User::factory(),
            'resolution_notes' => fake()->paragraph(),
        ]);
    }

    public function critical(): static
    {
        return $this->state(fn(array $attributes) => [
            'severity' => 'critical',
            'threat_score' => fake()->numberBetween(80, 100),
        ]);
    }
}
