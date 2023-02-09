<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $date = Carbon::now('Asia/Singapore');

        DB::table('status')->insert([
            'name' => 'New Request',
            'created_at' => $date,
            'updated_at' => $date,
        ]);
        DB::table('status')->insert([
            'name' => 'In Progress',
            'created_at' => $date,
            'updated_at' => $date,
        ]);
        DB::table('status')->insert([
            'name' => 'Hold',
            'created_at' => $date,
            'updated_at' => $date,
        ]);
        DB::table('status')->insert([
            'name' => 'Solved',
            'created_at' => $date,
            'updated_at' => $date,
        ]);
        DB::table('status')->insert([
            'name' => 'Closed',
            'created_at' => $date,
            'updated_at' => $date,
        ]);
    }
}
