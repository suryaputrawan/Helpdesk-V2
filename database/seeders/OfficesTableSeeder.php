<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('offices')->insert([
            'name' => 'SHBC',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('offices')->insert([
            'name' => 'SILOAM MEDIKA CANGGU',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('offices')->insert([
            'name' => 'BIMC UBUD',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
