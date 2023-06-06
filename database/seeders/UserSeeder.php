<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
                'first_name' => 'Milestone',
                'last_name' => 'admin',
                'username' => 'admin',
                'email' => 'admin@milestone.com',
                'password' => bcrypt('admin'),
                'phone' => '0911111111',
                'gender' => 'MALE',
            ],
        ]);
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
        ]);
        DB::table('admins')->insert([
            [
                'user_id' => 1,
            ],
        ]);
        DB::table('role_user')->insert([
            [
                'user_id' => 1,
                'role_id' => 1,
                'user_type' => 'App\Models\User',
            ],
            [
                'user_id' => 1,
                'role_id' => 2,
                'user_type' => 'App\Models\User',
            ],
            [
                'user_id' => 1,
                'role_id' => 3,
                'user_type' => 'App\Models\User',
            ],
            [
                'user_id' => 1,
                'role_id' => 5,
                'user_type' => 'App\Models\User',
            ],
        ]);
    }
}
