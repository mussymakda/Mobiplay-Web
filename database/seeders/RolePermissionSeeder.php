<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // System permissions
            ['name' => 'access_admin_panel', 'display_name' => 'Access Admin Panel', 'category' => 'system', 'description' => 'Can access the admin panel'],
            ['name' => 'manage_system', 'display_name' => 'Manage System', 'category' => 'system', 'description' => 'Full system management access'],
            ['name' => 'view_system_logs', 'display_name' => 'View System Logs', 'category' => 'system', 'description' => 'Can view system logs and analytics'],

            // User management permissions
            ['name' => 'manage_users', 'display_name' => 'Manage Users', 'category' => 'users', 'description' => 'Create, edit, delete users'],
            ['name' => 'view_users', 'display_name' => 'View Users', 'category' => 'users', 'description' => 'View user listings and profiles'],
            ['name' => 'manage_drivers', 'display_name' => 'Manage Drivers', 'category' => 'users', 'description' => 'Manage driver accounts and verification'],
            ['name' => 'verify_drivers', 'display_name' => 'Verify Drivers', 'category' => 'users', 'description' => 'Approve or reject driver verification'],

            // Admin management permissions
            ['name' => 'manage_admins', 'display_name' => 'Manage Admins', 'category' => 'admins', 'description' => 'Create, edit, delete admin accounts'],
            ['name' => 'view_admins', 'display_name' => 'View Admins', 'category' => 'admins', 'description' => 'View admin listings'],
            ['name' => 'manage_roles', 'display_name' => 'Manage Roles', 'category' => 'admins', 'description' => 'Create, edit, delete roles and permissions'],

            // Campaign management permissions
            ['name' => 'manage_campaigns', 'display_name' => 'Manage Campaigns', 'category' => 'campaigns', 'description' => 'Create, edit, delete campaigns'],
            ['name' => 'view_campaigns', 'display_name' => 'View Campaigns', 'category' => 'campaigns', 'description' => 'View campaign listings'],
            ['name' => 'approve_campaigns', 'display_name' => 'Approve Campaigns', 'category' => 'campaigns', 'description' => 'Approve or reject campaigns'],
            ['name' => 'view_campaign_analytics', 'display_name' => 'View Campaign Analytics', 'category' => 'campaigns', 'description' => 'Access campaign performance data'],

            // Ad management permissions
            ['name' => 'manage_ads', 'display_name' => 'Manage Ads', 'category' => 'ads', 'description' => 'Create, edit, delete advertisements'],
            ['name' => 'view_ads', 'display_name' => 'View Ads', 'category' => 'ads', 'description' => 'View ad listings'],
            ['name' => 'approve_ads', 'display_name' => 'Approve Ads', 'category' => 'ads', 'description' => 'Approve or reject advertisements'],
            ['name' => 'moderate_content', 'display_name' => 'Moderate Content', 'category' => 'ads', 'description' => 'Review and moderate ad content'],

            // Financial permissions
            ['name' => 'manage_payments', 'display_name' => 'Manage Payments', 'category' => 'finance', 'description' => 'Handle payment processing and refunds'],
            ['name' => 'view_transactions', 'display_name' => 'View Transactions', 'category' => 'finance', 'description' => 'View transaction history'],
            ['name' => 'manage_packages', 'display_name' => 'Manage Packages', 'category' => 'finance', 'description' => 'Create, edit pricing packages'],

            // Support permissions
            ['name' => 'manage_support', 'display_name' => 'Manage Support', 'category' => 'support', 'description' => 'Handle customer support tickets'],
            ['name' => 'view_support', 'display_name' => 'View Support', 'category' => 'support', 'description' => 'View support requests'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        // Create roles
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access with all permissions',
                'permissions' => Permission::pluck('name')->toArray(), // All permissions
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'General admin access with most permissions',
                'permissions' => [
                    'access_admin_panel', 'view_system_logs',
                    'manage_users', 'view_users', 'manage_drivers', 'verify_drivers',
                    'view_admins',
                    'manage_campaigns', 'view_campaigns', 'approve_campaigns', 'view_campaign_analytics',
                    'manage_ads', 'view_ads', 'approve_ads', 'moderate_content',
                    'view_transactions', 'manage_packages',
                    'manage_support', 'view_support',
                ],
            ],
            [
                'name' => 'moderator',
                'display_name' => 'Content Moderator',
                'description' => 'Content moderation and basic user management',
                'permissions' => [
                    'access_admin_panel',
                    'view_users', 'manage_drivers', 'verify_drivers',
                    'view_campaigns', 'approve_campaigns',
                    'view_ads', 'approve_ads', 'moderate_content',
                    'view_support', 'manage_support',
                ],
            ],
            [
                'name' => 'content_manager',
                'display_name' => 'Content Manager',
                'description' => 'Manages campaigns and advertisements',
                'permissions' => [
                    'access_admin_panel',
                    'view_users',
                    'manage_campaigns', 'view_campaigns', 'view_campaign_analytics',
                    'manage_ads', 'view_ads',
                    'view_transactions',
                    'view_support',
                ],
            ],
            [
                'name' => 'support_agent',
                'display_name' => 'Support Agent',
                'description' => 'Customer support and basic user assistance',
                'permissions' => [
                    'access_admin_panel',
                    'view_users',
                    'view_campaigns',
                    'view_ads',
                    'manage_support', 'view_support',
                ],
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);

            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );

            // Sync permissions
            $role->syncPermissions($permissions);
        }
    }
}
