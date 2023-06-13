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

        $roles = [
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
        ];

        $adminRoleId = null;
        $receptionRoleId = null;
        $teacherRoleId = null;
        $studentRoleId = null;

        foreach ($roles as $role) {
            $roleId = DB::table('roles')->insertGetId($role);

            if ($role['name'] === 'Admin') {
                $adminRoleId = $roleId;
            } elseif ($role['name'] === 'Reception') {
                $receptionRoleId = $roleId;
            } elseif ($role['name'] === 'Teacher') {
                $teacherRoleId = $roleId;
            } elseif ($role['name'] === 'Student') {
                $studentRoleId = $roleId;
            }
        }

        DB::table('admins')->insert([
            [
                'user_id' => 1,
            ],
        ]);

        DB::table('role_user')->insert([
            [
                'user_id' => 1,
                'role_id' => $adminRoleId,
                'user_type' => 'App\Models\User',
            ],
            [
                'user_id' => 1,
                'role_id' => $receptionRoleId,
                'user_type' => 'App\Models\User',
            ],
            [
                'user_id' => 1,
                'role_id' => $teacherRoleId,
                'user_type' => 'App\Models\User',
            ],
            [
                'user_id' => 1,
                'role_id' => $studentRoleId,
                'user_type' => 'App\Models\User',
            ],
        ]);
    }
}
