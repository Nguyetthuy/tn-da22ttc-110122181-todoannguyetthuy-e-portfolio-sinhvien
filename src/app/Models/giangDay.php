<?php

namespace App\Models;

use App\Models\hocPhan;
use App\Models\lopHanhChinh;
use App\Models\giangVien;
use CompositeKeyModelHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class giangDay extends Model
{
    use HasFactory;
    protected $table='giangday';
    public $fillable=['maHocPhan','maLop','maGV','maHKNH','isDelete'];

    public function hocphan()
    {
        return $this->hasOne(hocPhan::class, 'maHocPhan', 'maHocPhan');
    }

    public function lop()
    {
        return $this->hasOne(lopHanhChinh::class, 'maLop', 'maLop');
    }

    public function giangvien()
    {
        return $this->hasOne(giangVien::class, 'maGV', 'maGV');
    }
    
}
