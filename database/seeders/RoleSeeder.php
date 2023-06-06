<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'Admin',
                'display_name' => 'admin',
                'description' => 'Adminstrator of the Milestone institute',
            ],
            [
                'name' => 'Reception',
                'display_name' => 'reception',
                'description' => 'Reception\'s employee of the Milestone institute',
            ],
            [
                'name' => 'Teacher',
                'display_name' => 'teacher',
                'description' => 'Teacher\'s employee of the Milestone institute',
            ],
            [
                'name' => 'Student',
                'display_name' => 'student',
                'description' => 'Student\'s of the Milestone institute',
            ],
            [
                'name' => 'HR',
                'display_name' => 'HR',
                'description' => 'HR\'s employee of the Milestone institute',
            ],
        ]);
    }
}
