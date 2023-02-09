<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'administrator',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('rahasia'),
            'remember_token' => Str::random(60),
            'office_id' => 1,
            'department_id' => 5,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'User Test',
            'username' => 'user',
            'email' => 'user@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('rahasia'),
            'remember_token' => Str::random(60),
            'office_id' => 2,
            'department_id' => 3,
            'role' => 'user',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
