<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesAndDepartmentsSeeder extends Seeder
{
    public function run()
    {
       
        // Seed departments
        DB::table('departments')->insert([
            ['id' => 1, 'name' => 'HR'],
            ['id' => 2, 'name' => 'IT'],
            ['id' => 3, 'name' => 'Finance'],
            ['id' => 4, 'name' => 'Marketing'],
        ]);
    }
}