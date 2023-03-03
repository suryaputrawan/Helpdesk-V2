<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(DepartmentTableSeeder::class);
        $this->call(OfficesTableSeeder::class);
        $this->call(ParamNumbersTableSeeder::class);
        // $this->call(StatusTableSeeder::class);
        $this->call(LocationTableSeeder::class);
    }
}
