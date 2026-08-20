<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlockedIpFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ip_address' => fake()->ipv4(),
            'reason' => fake()->sentence(),
            'blocked_by' => User::factory(),
            'is_permanent' => false,
            'blocked_at' => now(),
            'expires_at' => now()->addHours(24),
            'attempts' => fake()->numberBetween(3, 20),
            'metadata' => ['detection_method' => 'auto_block'],
        ];
    }

    public function permanent(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_permanent' => true,
            'expires_at' => null,
        ]);
    }
}
