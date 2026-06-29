<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class hocki_namhoc_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hocky_namhoc')->insert( [
            ['maHKNH'=>'HK1-2022-2023','tenHKNH'=>'2022-2023'],
        ]);
    }
}
