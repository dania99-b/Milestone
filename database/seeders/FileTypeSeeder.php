<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class FileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('file_types')->insert([
            [
                'name' => 'Audio files',
            ],
            [
                'name' => 'Video files',
            ],
            [
                'name' => 'Resources',
            ],
        ]);
    }
}
