<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $read_auth_user = Permission::create(['name' => 'read-auth-user', 'display_name' => 'Read User', 'description' => 'Read User']);
        $create_auth_user = Permission::create(['name' => 'create-auth-user', 'display_name' => 'Create User', 'description' => 'Create User']);
        $update_auth_user = Permission::create(['name' => 'update-auth-user', 'display_name' => 'Update User', 'description' => 'Update User']);
        $delete_auth_user = Permission::create(['name' => 'delete-auth-user', 'display_name' => 'Delete User', 'description' => 'Delete User']);
        $read_auth_role = Permission::create(['name' => 'read-auth-role', 'display_name' => 'Read Role', 'description' => 'Read Role']);
        $create_auth_role = Permission::create(['name' => 'create-auth-role', 'display_name' => 'Create Role', 'description' => 'Create Role']);
        $update_auth_role = Permission::create(['name' => 'update-auth-role', 'display_name' => 'Update Role', 'description' => 'Update Role']);
        $delete_auth_role = Permission::create(['name' => 'delete-auth-role', 'display_name' => 'Delete Role', 'description' => 'Delete Role']);
        $read_auth_permission = Permission::create(['name' => 'read-auth-permission', 'display_name' => 'Read Permission', 'description' => 'Read Permission']);
        $create_auth_permission = Permission::create(['name' => 'create-auth-permission', 'display_name' => 'Create Permission', 'description' => 'Create Permission']);
        $update_auth_permission = Permission::create(['name' => 'update-auth-permission', 'display_name' => 'Update Permission', 'description' => 'Update Permission']);
        $delete_auth_permission = Permission::create(['name' => 'delete-auth-permission', 'display_name' => 'Delete Permission', 'description' => 'Delete Permission']);
        $read_auth_menu = Permission::create(['name' => 'read-auth-menu', 'display_name' => 'Read Menu', 'description' => 'Read Menu']);
        $create_auth_menu = Permission::create(['name' => 'create-auth-menu', 'display_name' => 'Create Menu', 'description' => 'Create Menu']);
        $update_auth_menu = Permission::create(['name' => 'update-auth-menu', 'display_name' => 'Update Menu', 'description' => 'Update Menu']);
        $delete_auth_menu = Permission::create(['name' => 'delete-auth-menu', 'display_name' => 'Delete Menu', 'description' => 'Delete Menu']);

        $administrator = Role::query()->where('name', '=', 'administrator')->first();
        if ($administrator) {
            $administrator->attachPermissions([
                $read_auth_user,
                $create_auth_user,
                $update_auth_user,
                $delete_auth_user,
                $read_auth_role,
                $create_auth_role,
                $update_auth_role,
                $delete_auth_role,
                $read_auth_permission,
                $create_auth_permission,
                $update_auth_permission,
                $delete_auth_permission,
                $read_auth_menu,
                $create_auth_menu,
                $update_auth_menu,
                $delete_auth_menu,
            ]);
        }
    }
}
