<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create([
            'name' => 'administrator',
            'display_name' => 'Administrator',
            'description' => 'User is the owner of a given project'
        ]);
        $user = User::query()->where('email', '=', 'fath.hadzami@mitracomm.com')->first();
        if ($user) {
            $user->attachRole($role);
        }
    }
}
