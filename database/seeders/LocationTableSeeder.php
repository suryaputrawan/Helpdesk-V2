<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->insert([
            'name' => 'Front Office',
            'office_id' => '2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('locations')->insert([
            'name' => 'Observe 1',
            'office_id' => '3',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('locations')->insert([
            'name' => 'Observe 2',
            'office_id' => '3',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('locations')->insert([
            'name' => 'Consult 1',
            'office_id' => '2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('locations')->insert([
            'name' => 'Consult 2',
            'office_id' => '3',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('locations')->insert([
            'name' => 'Nurse Station',
            'office_id' => '2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('locations')->insert([
            'name' => 'Treatment',
            'office_id' => '2',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
