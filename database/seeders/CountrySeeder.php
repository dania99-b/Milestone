<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            [
                'name' => 'Abu Ramana',
            ],
            [
                'name' => 'Al-Amin',
            ],
            [
                'name' => 'Barmakeh',
            ],
            [
                'name' => 'Al-Hijaz',
            ],
            [
                'name' => 'Hariga',
            ],
            [
                'name' => 'Al-Shaalan',
            ],
            [
                'name' => 'Assali',
            ],
            [
                'name' => 'Al-Amara',
            ],
            [
                'name' => 'Al-Qaboun',
            ],
            [
                'name' => 'Al-Qassa\'',
            ],
            [
                'name' => 'Midan',
            ],
            [
                'name' => 'Bab Sharqi',
            ],
            [
                'name' => 'Bab Touma',
            ],
            [
                'name' => 'Al-Qamiri',
            ],
            [
                'name' => 'Barzeh',
            ],
            [
                'name' => 'Masaken Barzeh',
            ],
            [
                'name' => 'Rukn al-Din',
            ],
            [
                'name' => 'Jobar',
            ],
            [
                'name' => 'Al-Mazra \'',
            ],
            [
                'name' => 'Al-Mohagreen',
            ],
            [
                'name' => 'Al-Zuhur',
            ],
            [
                'name' => 'Duallah',
            ],
            [
                'name' => 'Saroja',
            ],
            [
                'name' => 'al-Shaghour'
            ],
            [
                'name' => 'Al-Salihiyah'
            ],
            [
                'name' => 'Jaramana'
            ],
            [
                'name' => 'Sahnaya'
            ],
            [
                'name' => 'Al-Qadam'
            ],
            [
                'name' => 'Kafrsoussa'
            ],
            [
                'name' => 'Mazzeh'
            ],
            [
                'name' => 'Yarmouk camp'
            ],
        ]);
    }
}
