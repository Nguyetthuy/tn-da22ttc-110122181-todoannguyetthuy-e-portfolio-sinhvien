<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class coVanLop extends Model
{
    protected $table = 'co_van_lop';
    protected $fillable = ['maGV', 'maLop', 'ngayBatDau', 'ngayKetThuc', 'isDelete'];
    public $timestamps = true;

    public function giangVien()
    {
        return $this->belongsTo(giangVien::class, 'maGV', 'maGV');
    }

    public function lop()
    {
        return $this->belongsTo(lopHanhChinh::class, 'maLop', 'maLop');
    }

    public function sinhVien()
    {
        return $this->hasMany(sinhVien::class, 'maLop', 'maLop');
    }

    public function lopHanhChinh()
    {
        return $this->belongsTo(lopHanhChinh::class, 'maLop', 'maLop');
    }
}