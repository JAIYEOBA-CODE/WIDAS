<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SystemSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->word(),
            'value' => fake()->sentence(),
            'group' => fake()->randomElement(['general', 'security', 'detection', 'notifications']),
            'description' => fake()->sentence(),
            'is_editable' => true,
        ];
    }
}
