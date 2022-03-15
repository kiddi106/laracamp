<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $created_at = '2021-01-01 00:00:00';
        DB::table('menus')->insert([
            ['id' => 1, 'menu_id' => null, 'name' => 'auth', 'display_name' => 'Auth', 'order_no' => null, 'url' => '', 'icon' => 'fas fa-key', 'created_at' => $created_at],
            ['id' => 2, 'menu_id' => 1, 'name' => 'user', 'display_name' => 'User', 'order_no' => 1, 'url' => '/auth/user', 'icon' => 'far fa-user', 'created_at' => $created_at],
            ['id' => 3, 'menu_id' => 1, 'name' => 'role', 'display_name' => 'Role', 'order_no' => 2, 'url' => '/auth/role', 'icon' => 'fas fa-arrows-alt', 'created_at' => $created_at],
            ['id' => 4, 'menu_id' => 1, 'name' => 'menu', 'display_name' => 'Menu', 'order_no' => 3, 'url' => '/auth/menu', 'icon' => 'fas fa-list', 'created_at' => $created_at],
            ['id' => 5, 'menu_id' => 1, 'name' => 'permission', 'display_name' => 'Permission', 'order_no' => 4, 'url' => '/auth/permission', 'icon' => 'fas fa-user-secret', 'created_at' => $created_at]
        ]);

        $administrator = Role::query()->where('name', '=', 'administrator')->first();
        if ($administrator) {
            DB::table('menu_role')->insert([
                ['menu_id' => 1, 'role_id' => $administrator->id],
                ['menu_id' => 2, 'role_id' => $administrator->id],
                ['menu_id' => 3, 'role_id' => $administrator->id],
                ['menu_id' => 4, 'role_id' => $administrator->id],
                ['menu_id' => 5, 'role_id' => $administrator->id]
            ]);
        }
    }
}
