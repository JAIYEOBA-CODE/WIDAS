<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ThreatRuleFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['brute_force', 'sql_injection', 'xss', 'session_abuse', 'unauthorized_access', 'api_abuse'];
        $severities = ['low', 'medium', 'high', 'critical'];
        $name = fake()->unique()->sentence(3);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'category' => fake()->randomElement($categories),
            'severity' => fake()->randomElement($severities),
            'threat_score' => fake()->numberBetween(10, 100),
            'patterns' => [fake()->word()],
            'config' => ['enabled' => true, 'threshold' => fake()->numberBetween(3, 20)],
            'is_active' => true,
            'auto_block' => fake()->boolean(),
            'threshold' => fake()->numberBetween(3, 20),
            'action' => fake()->randomElement(['log', 'alert', 'block']),
        ];
    }
}
