<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class users_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            // Admin (Quyền 1) - Mật khẩu: admin - Cần thiết cho khóa ngoại của sinh viên
            ['username' => 'admin', 'password' => md5('admin'), 'permission' => 1, 'isBlock' => false],
            
            // Cố vấn học tập (Quyền 6) - Mật khẩu: 123
            ['username' => 'ptpnam', 'password' => md5('123'), 'permission' => 6, 'isBlock' => false],
            ['username' => 'pttmai', 'password' => md5('123'), 'permission' => 6, 'isBlock' => false],
            
            // Giảng viên khác (Quyền 4) - Cần thiết cho khóa ngoại của giảng viên
            ['username' => 'xetnghiem', 'password' => md5('123'), 'permission' => 4, 'isBlock' => false],
            
            // Sinh viên (Quyền 5) - Mật khẩu: 123
            ['username' => '110116006', 'password' => md5('123'), 'permission' => 5, 'isBlock' => false],
            ['username' => '110116011', 'password' => md5('123'), 'permission' => 5, 'isBlock' => false],
        ]);
    }
}
