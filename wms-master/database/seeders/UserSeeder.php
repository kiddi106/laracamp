<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Fath Hadzami',
                'email' => 'fath.hadzami@mitracomm.com',
                'password' => bcrypt('12345679')
            ],[
                'name' => 'Bambang Suryadi',
                'email' => 'bambang.suryadi@mitracomm.com',
                'password' => bcrypt('12345678')
            ],[
                'name' => 'Budi Rinaldo',
                'email' => 'budi.rinaldo@mitracomm.com',
                'password' => bcrypt('12345678')
            ],[
                'name' => 'Dameria Gultom',
                'email' => 'dameria.gultom@mitracomm.com',
                'password' => bcrypt('12345678')
            ],[
                'name' => 'Denny Saputra',
                'email' => 'denny.saputra@mitracomm.com',
                'password' => bcrypt('12345678')
            ],[
                'name' => 'Asri Yanthi Makale',
                'email' => 'asri.makale@mitracomm.com',
                'password' => bcrypt('12345678')
            ],[
                'name' => 'Telkomsel Orbit',
                'email' => 'orbit@mail.com',
                'password' => bcrypt('TS#l0rb!t')
            ]
        ]);
    }
}
