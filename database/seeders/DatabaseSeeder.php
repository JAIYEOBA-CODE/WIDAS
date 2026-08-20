<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            ThreatRuleSeeder::class,
            SystemSettingSeeder::class,
        ]);

        User::firstOrCreate(
            ['email' => 'admin@widas.test'],
            ['name' => 'Admin User', 'password' => bcrypt('password'), 'role_id' => \App\Models\Role::where('slug', 'admin')->first()?->id, 'email_verified_at' => now()]
        );

        User::firstOrCreate(
            ['email' => 'analyst@widas.test'],
            ['name' => 'Security Analyst', 'password' => bcrypt('password'), 'role_id' => \App\Models\Role::where('slug', 'analyst')->first()?->id, 'email_verified_at' => now()]
        );

        User::firstOrCreate(
            ['email' => 'user@widas.test'],
            ['name' => 'Regular User', 'password' => bcrypt('password'), 'role_id' => \App\Models\Role::where('slug', 'user')->first()?->id, 'email_verified_at' => now()]
        );

        if (app()->environment('local')) {
            User::factory()->count(10)->user()->create();
            User::factory()->count(3)->analyst()->create();

            \App\Models\IntrusionEvent::factory()->count(50)->create();
            \App\Models\IntrusionEvent::factory()->count(10)->critical()->create();
            \App\Models\Alert::factory()->count(30)->create();
            \App\Models\Alert::factory()->count(10)->critical()->create();
            \App\Models\LoginAttempt::factory()->count(100)->create();
            \App\Models\BlockedIp::factory()->count(5)->create();
            \App\Models\SecurityLog::factory()->count(50)->create();
        }
    }
}
