<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParamNumbersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('param_numbers')->insert([
            'ticketNo' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
