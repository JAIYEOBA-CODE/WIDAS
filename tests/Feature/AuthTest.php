<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role_id' => Role::where('slug', 'user')->first()->id,
                'email_verified_at' => now(),
            ]
        );

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect();
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'test2@example.com'],
            [
                'name' => 'Test User 2',
                'password' => bcrypt('password'),
                'role_id' => Role::where('slug', 'user')->first()->id,
                'email_verified_at' => now(),
            ]
        );

        $response = $this->post('/login', [
            'email' => 'test2@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'test3@example.com'],
            [
                'name' => 'Test User 3',
                'password' => bcrypt('password'),
                'role_id' => Role::where('slug', 'user')->first()->id,
                'email_verified_at' => now(),
            ]
        );

        $this->actingAs($user);
        $response = $this->post('/logout');
        $this->assertGuest();
    }
}
