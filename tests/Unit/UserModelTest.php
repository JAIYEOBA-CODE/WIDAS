<?php

namespace Tests\Unit;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_user_has_role_relationship(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $user = User::factory()->admin()->create();

        $this->assertInstanceOf(Role::class, $user->role);
        $this->assertEquals($adminRole->id, $user->role->id);
    }

    public function test_user_can_check_role(): void
    {
        $user = User::factory()->admin()->create();

        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isAnalyst());
        $this->assertFalse($user->isUser());
    }

    public function test_user_can_check_permission(): void
    {
        $user = User::factory()->admin()->create();
        $permission = Permission::where('slug', 'view-users')->first();

        $this->assertTrue($user->hasPermission('view-users'));
    }

    public function test_user_can_be_locked(): void
    {
        $user = User::factory()->create([
            'locked_until' => now()->addMinutes(30),
        ]);

        $this->assertTrue($user->isLocked());
    }

    public function test_user_scope_active(): void
    {
        User::query()->delete();
        User::factory()->count(3)->create(['is_active' => true]);
        User::factory()->create(['is_active' => false]);

        $this->assertEquals(3, User::active()->count());
    }
}
