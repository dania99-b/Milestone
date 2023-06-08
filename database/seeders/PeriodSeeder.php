<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('periods')->insert([
            [
                'start_hour' => '09:00',
                'end_hour' => '11:00',
                'is_available'=>0
               
            ],
            [
                'start_hour' => '11:00',
                'end_hour' => '01:00',
                'is_available'=>0
               
            ],
            [
                'start_hour' => '01:00',
                'end_hour' => '03:00',
                'is_available'=>0
               
            ],
            [
                'start_hour' => '03:00',
                'end_hour' => '05:00',
                'is_available'=>0
               
            ],
            [
                'start_hour' => '05:00',
                'end_hour' => '07:00',
                'is_available'=>0
               
            ],
            [
                'start_hour' => '07:00',
                'end_hour' => '09:00',
                'is_available'=>0
               
            ],
        ]);
    }
}
