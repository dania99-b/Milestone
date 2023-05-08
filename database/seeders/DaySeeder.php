<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('days')->insert([


            [
                'name' => 'Saturday',
                'is_vacation' => 0,

            ],
            [
                'name' => 'Sunday',
                'is_vacation' => 0,
            ],
            [
                'name' => 'Monday',
                'is_vacation' => 0,

            ], [
                'name' => 'Tuesday',
                'is_vacation' => 0,

            ],
            [
                'name' => 'Wednesday',
                'is_vacation' => 0,

            ],
            [
                'name' => 'Thursday',
                'is_vacation' => 0,

            ],
            [
                'name' => 'Friday',
                'is_vacation' => 1,

            ],
        ]);
    }
}
