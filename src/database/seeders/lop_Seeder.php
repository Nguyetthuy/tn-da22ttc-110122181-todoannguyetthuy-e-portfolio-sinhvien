<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class lop_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lop_hanh_chinh')->insert([
           ['maLop'=>'DA16TT','tenLop'=>'ĐH Công nghệ thông tin 2016','maKhoaTuyenSinh'=>'1','maCT'=>1]
         ]);

        DB::table('co_van_lop')->insert([
            ['maGV'=>'1234','maLop'=>'DA16TT','ngayBatDau'=>'2020-09-01','ngayKetThuc'=>'2026-06-30','isDelete'=>false],
            ['maGV'=>'8452','maLop'=>'DA16TT','ngayBatDau'=>'2020-09-01','ngayKetThuc'=>'2026-06-30','isDelete'=>false]
        ]);
    }
}
