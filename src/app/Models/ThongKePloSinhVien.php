<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThongKePloSinhVien extends Model
{
    use HasFactory;
    protected $table = 'thongke_plo_sinhvien';
    protected $fillable = [
        'maSSV',
        'maHocPhan',
        'maHKNH',
        'maCDR_CTDT',
        'ty_le_dat',
        'ty_le_dong_gop',
    ];
}
