<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class khoaTS_ctdt_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('khoa_tuyensinh_ct_daotao')->insert([
            ['id'=>1,'maKhoaTuyenSinh'=>1,'maCT'=>1,'maCDR_CTDT'=>1],
            ['id'=>2,'maKhoaTuyenSinh'=>1,'maCT'=>1,'maCDR_CTDT'=>2],
            ['id'=>3,'maKhoaTuyenSinh'=>1,'maCT'=>1,'maCDR_CTDT'=>3],
            ['id'=>4,'maKhoaTuyenSinh'=>1,'maCT'=>1,'maCDR_CTDT'=>4],
            ['id'=>5,'maKhoaTuyenSinh'=>1,'maCT'=>1,'maCDR_CTDT'=>5],
            ['id'=>6,'maKhoaTuyenSinh'=>1,'maCT'=>1,'maCDR_CTDT'=>6],
            ['id'=>7,'maKhoaTuyenSinh'=>1,'maCT'=>1,'maCDR_CTDT'=>7],
            ['id'=>8,'maKhoaTuyenSinh'=>1,'maCT'=>1,'maCDR_CTDT'=>8],
            ['id'=>9,'maKhoaTuyenSinh'=>1,'maCT'=>1,'maCDR_CTDT'=>9],
            ['id'=>10,'maKhoaTuyenSinh'=>1,'maCT'=>1,'maCDR_CTDT'=>10],
         ]);
    }
}
