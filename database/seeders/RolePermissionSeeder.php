<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Admin', 'description' => 'Full system access with all permissions']
        );

        $analystRole = Role::firstOrCreate(
            ['slug' => 'analyst'],
            ['name' => 'Security Analyst', 'description' => 'Monitor threats, review alerts, investigate incidents']
        );

        $userRole = Role::firstOrCreate(
            ['slug' => 'user'],
            ['name' => 'User', 'description' => 'Basic user with limited access']
        );

        $permissions = [
            // User management
            ['name' => 'View Users', 'slug' => 'view-users', 'description' => 'View user list'],
            ['name' => 'Create Users', 'slug' => 'create-users', 'description' => 'Create new users'],
            ['name' => 'Edit Users', 'slug' => 'edit-users', 'description' => 'Edit existing users'],
            ['name' => 'Delete Users', 'slug' => 'delete-users', 'description' => 'Delete users'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'description' => 'Manage roles and permissions'],

            // Alert management
            ['name' => 'View Alerts', 'slug' => 'view-alerts', 'description' => 'View alert list'],
            ['name' => 'Manage Alerts', 'slug' => 'manage-alerts', 'description' => 'Manage and resolve alerts'],
            ['name' => 'Acknowledge Alerts', 'slug' => 'acknowledge-alerts', 'description' => 'Acknowledge alerts'],

            // Threat management
            ['name' => 'View Threats', 'slug' => 'view-threats', 'description' => 'View threat list'],
            ['name' => 'Manage Threats', 'slug' => 'manage-threats', 'description' => 'Manage and resolve threats'],
            ['name' => 'Create Threat Rules', 'slug' => 'create-threat-rules', 'description' => 'Create detection rules'],

            // Dashboard
            ['name' => 'View Admin Dashboard', 'slug' => 'view-admin-dashboard', 'description' => 'View admin dashboard'],
            ['name' => 'View Analyst Dashboard', 'slug' => 'view-analyst-dashboard', 'description' => 'View analyst dashboard'],
            ['name' => 'View User Dashboard', 'slug' => 'view-user-dashboard', 'description' => 'View user dashboard'],

            // Reports
            ['name' => 'View Reports', 'slug' => 'view-reports', 'description' => 'View security reports'],
            ['name' => 'Generate Reports', 'slug' => 'generate-reports', 'description' => 'Generate security reports'],
            ['name' => 'Export Reports', 'slug' => 'export-reports', 'description' => 'Export reports'],

            // Settings
            ['name' => 'View Settings', 'slug' => 'view-settings', 'description' => 'View system settings'],
            ['name' => 'Manage Settings', 'slug' => 'manage-settings', 'description' => 'Manage system settings'],

            // Audit & Logs
            ['name' => 'View Audit Logs', 'slug' => 'view-audit-logs', 'description' => 'View audit logs'],
            ['name' => 'View Activity Logs', 'slug' => 'view-activity-logs', 'description' => 'View activity logs'],

            // IP Management
            ['name' => 'View Blocked IPs', 'slug' => 'view-blocked-ips', 'description' => 'View blocked IPs'],
            ['name' => 'Manage Blocked IPs', 'slug' => 'manage-blocked-ips', 'description' => 'Manage blocked IPs'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['slug' => $perm['slug']], $perm);
        }

        // Assign all permissions to admin
        $adminRole->permissions()->syncWithoutDetaching(Permission::all()->pluck('id'));

        // Assign analyst permissions
        $analystPermissions = Permission::whereIn('slug', [
            'view-alerts', 'manage-alerts', 'acknowledge-alerts',
            'view-threats', 'manage-threats',
            'view-analyst-dashboard',
            'view-reports', 'generate-reports', 'export-reports',
            'view-activity-logs',
            'view-blocked-ips',
        ])->pluck('id');
        $analystRole->permissions()->syncWithoutDetaching($analystPermissions);

        // Assign user permissions
        $userPermissions = Permission::whereIn('slug', [
            'view-user-dashboard',
            'view-alerts', 'acknowledge-alerts',
        ])->pluck('id');
        $userRole->permissions()->syncWithoutDetaching($userPermissions);
    }
}
