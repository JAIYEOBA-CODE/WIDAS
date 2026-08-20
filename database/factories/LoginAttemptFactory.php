<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoginAttemptFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'email' => fake()->email(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'was_successful' => fake()->boolean(30),
            'failure_reason' => 'invalid_password',
            'metadata' => ['browser' => fake()->chrome()],
        ];
    }

    public function failed(): static
    {
        return $this->state(fn(array $attributes) => [
            'was_successful' => false,
        ]);
    }

    public function successful(): static
    {
        return $this->state(fn(array $attributes) => [
            'was_successful' => true,
            'failure_reason' => null,
        ]);
    }
}
