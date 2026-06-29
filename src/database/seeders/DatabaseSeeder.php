<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            users_Seeder::class,  //user
            hocki_namhoc_Seeder::class, //học kì năm học
            giang_vien_Seeder::class,  //giảng viên
            ct_dao_tao_Seeder::class,  //chi tiết đào tạo
            khoa_tuyen_sinh_Seeder::class,
            cdr_ctdt_Seeder::class,
            khoaTS_ctdt_Seeder::class,
            lop_Seeder::class, //lớp (chứa cả cố vấn lớp)
            sinh_vien_Seeder::class, //sinh viên
        ]);
    }
}
