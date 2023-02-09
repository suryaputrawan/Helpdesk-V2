<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            'name' => 'ICT',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('departments')->insert([
            'name' => 'Engineering',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('departments')->insert([
            'name' => 'Front Office',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('departments')->insert([
            'name' => 'Nurse',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('departments')->insert([
            'name' => 'Administrator',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
