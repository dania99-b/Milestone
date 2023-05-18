<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CourseNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course_names')->insert([
            [
                'name' => '1A',
            ],
            [
                'name' => '1B',
            ],
            [
                'name' => '2A',
            ],
            [
                'name' => '2B',
            ],
            [
                'name' => '3A',
            ],
            [
                'name' => '3B',
            ],
            [
                'name' => '4A',
            ],
            [
                'name' => '4B',
            ],
            [
                'name' => '5A',
            ],
            [
                'name' => '5B',
            ],
        ]);
    }
}
