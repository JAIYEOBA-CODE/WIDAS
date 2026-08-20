<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SecurityLogFactory extends Factory
{
    public function definition(): array
    {
        $types = ['login', 'logout', 'failed_login', 'suspicious_activity', 'block', 'unblock'];
        $severities = ['info', 'warning', 'danger', 'critical'];

        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement($types),
            'severity' => fake()->randomElement($severities),
            'source_ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'metadata' => ['location' => fake()->city()],
            'message' => fake()->sentence(),
        ];
    }
}
