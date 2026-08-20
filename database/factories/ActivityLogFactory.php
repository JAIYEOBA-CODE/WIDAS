<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityLogFactory extends Factory
{
    public function definition(): array
    {
        $actions = ['login', 'logout', 'create', 'update', 'delete', 'view', 'export'];
        $modules = ['users', 'alerts', 'reports', 'settings', 'threats', 'dashboard'];

        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement($actions),
            'module' => fake()->randomElement($modules),
            'description' => fake()->sentence(),
            'old_values' => ['key' => 'old_value'],
            'new_values' => ['key' => 'new_value'],
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }
}
