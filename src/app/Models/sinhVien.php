<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sinhVien extends Model
{
    use HasFactory;
    protected $table='sinh_vien';
    protected $primaryKey = 'maSSV';
    public $incrementing = false;
    protected $fillable = ['maSSV','HoSV','TenSV','Phai','NgaySinh','maLop','isDelete'];

    //-------------------function-------------------------------------------
    public static function get_sv_by_massv($maSSV)
    {
        return self::where('isDelete',false)
        ->where('maSSV',$maSSV)
        ->first();
    }
}
