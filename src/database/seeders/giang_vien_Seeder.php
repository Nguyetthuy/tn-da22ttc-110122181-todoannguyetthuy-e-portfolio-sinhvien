<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class giang_vien_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('giang_vien')->insert([
            ['maGV'=>'1234','hoGV'=>'Phan','tenGV'=>'Thị Phương Nam','username'=>'ptpnam','maBM'=>1], //admin
            ['maGV'=>'8452','hoGV'=>'Phạm','tenGV'=>'Thị Trúc Mai','username'=>'pttmai','maBM'=>1], //admin
            ['maGV'=>'2020','hoGV'=>'Lê','tenGV'=>'Hồng Phong','username'=>'xetnghiem','maBM'=>2], //admin
            
         ]);
    }
}
