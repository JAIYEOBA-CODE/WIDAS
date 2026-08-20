<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_active' => true,
            'login_attempts' => 0,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role_id' => Role::where('slug', 'admin')->first()?->id,
        ]);
    }

    public function analyst(): static
    {
        return $this->state(fn(array $attributes) => [
            'role_id' => Role::where('slug', 'analyst')->first()?->id,
        ]);
    }

    public function user(): static
    {
        return $this->state(fn(array $attributes) => [
            'role_id' => Role::where('slug', 'user')->first()?->id,
        ]);
    }
}
