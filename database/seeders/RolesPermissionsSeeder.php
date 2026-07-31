<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'advertiser', 'media_manager', 'accountant'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r], ['guard_name' => 'api']);
        }

        $permissions = [
            'manage_users', 'manage_roles', 'manage_media', 'manage_campaigns',
            'manage_payments', 'view_reports', 'view_statistics', 'manage_settings'
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p], ['guard_name' => 'api']);
        }
    }
}
