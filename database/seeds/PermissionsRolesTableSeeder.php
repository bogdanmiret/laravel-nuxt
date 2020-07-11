<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $role1 = Role::create(['name' => 'user', 'guard_name' => 'web']);
        $role2 = Role::create(['name' => 'admin', 'guard_name' => 'web']);

        $role3 = Role::create(['name' => 'super-admin', 'guard_name' => 'web']);

        $permission = Permission::create(['name' => 'view admin', 'guard_name' => 'web']);

        $permission->assignRole($role2);
    }
}
