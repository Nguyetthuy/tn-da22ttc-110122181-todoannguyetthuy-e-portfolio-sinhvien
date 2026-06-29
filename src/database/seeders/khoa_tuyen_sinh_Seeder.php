<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class khoa_tuyen_sinh_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('khoa_tuyensinh')->insert([
            ['maKhoaTuyenSinh'=>1,'namTS'=>'2021-2022']
         ]);
    }
}
