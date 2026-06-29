<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class sinh_vien_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sinh_vien')->insert([
            ['maSSV'=>'110116006','HoSV'=>'Hứa Thanh','TenSV'=>'Bình','Phai'=>'"Nam"','NgaySinh'=>'"19/01/1998"','maLop'=>'DA16TT','username'=>'admin'],
            ['maSSV'=>'110116011','HoSV'=>'Phạm Long','TenSV'=>'Đĩnh','Phai'=>'"Nam"','NgaySinh'=>'"18/05/1998"','maLop'=>'DA16TT','username'=>'admin'],
            ['maSSV'=>'110116018','HoSV'=>'Phạm Nhựt','TenSV'=>'Duy','Phai'=>'"Nam"','NgaySinh'=>'"04/01/1998"','maLop'=>'DA16TT','username'=>'admin'],
            ['maSSV'=>'110116034','HoSV'=>'Lê Hồ Anh','TenSV'=>'Khoa','Phai'=>'"Nam"','NgaySinh'=>'"19/05/1998"','maLop'=>'DA16TT','username'=>'admin'],
            ['maSSV'=>'110116042','HoSV'=>'Huỳnh Châu Thế','TenSV'=>'Mỹ','Phai'=>'"Nam"','NgaySinh'=>'"18/09/1998"','maLop'=>'DA16TT','username'=>'admin'],
            ['maSSV'=>'110116044','HoSV'=>'Cao Mộng','TenSV'=>'Ngân','Phai'=>'"Nam"','NgaySinh'=>'"21/02/1997"','maLop'=>'DA16TT','username'=>'admin'],
            ['maSSV'=>'110116045','HoSV'=>'Dương Thái','TenSV'=>'Ngọc','Phai'=>'"Nam"','NgaySinh'=>'"14/06/1998"','maLop'=>'DA16TT','username'=>'admin'],
            ['maSSV'=>'110116046','HoSV'=>'Nguyễn Cao','TenSV'=>'Nhân','Phai'=>'"Nam"','NgaySinh'=>'"05/01/1998"','maLop'=>'DA16TT','username'=>'admin'],
            ['maSSV'=>'110116051','HoSV'=>'Phạm Thị Yến','TenSV'=>'Nhi','Phai'=>'"Nam"','NgaySinh'=>'"09/01/1998"','maLop'=>'DA16TT','username'=>'admin'],
            ['maSSV'=>'110116054','HoSV'=>'Thạch Đa','TenSV'=>'Ny','Phai'=>'"Nam"','NgaySinh'=>'"24/08/1996"','maLop'=>'DA16TT','username'=>'admin'],
            ['maSSV'=>'110116055','HoSV'=>'Trương Sơn Sô','TenSV'=>'Phol','Phai'=>'"Nam"','NgaySinh'=>'"17/03/1998"','maLop'=>'DA16TT','username'=>'admin'],
            ['maSSV'=>'110116057','HoSV'=>'Lý Gia','TenSV'=>'Quí','Phai'=>'"Nam"','NgaySinh'=>'"29/07/1998"','maLop'=>'DA16TT','username'=>'admin'],
            ['maSSV'=>'110116086','HoSV'=>'Tưởng Hoàng','TenSV'=>'Tỷ','Phai'=>'"Nam"','NgaySinh'=>'"10/02/1998"','maLop'=>'DA16TT','username'=>'admin'],
            ['maSSV'=>'110116087','HoSV'=>'Dư Khánh','TenSV'=>'Vinh','Phai'=>'"Nam"','NgaySinh'=>'"09/07/1998"','maLop'=>'DA16TT','username'=>'admin']
         ]);
    }
}
