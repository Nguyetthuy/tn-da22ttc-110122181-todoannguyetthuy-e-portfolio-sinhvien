<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\users;

class giangVien extends Model
{
    use HasFactory;
    protected $table='giang_vien';
    protected $primaryKey = 'maGV';
    public $incrementing=false;
    protected $fillable = ['maGV','hoGV','tenGV','username','email','isDelete','maBM'];

    public function user()
    {  
        return $this->belongsTo(users::class, 'username', 'username');
    }

    public function coVanLop()
    {
        return $this->belongsToMany(lopHanhChinh::class, 'co_van_lop', 'maGV', 'maLop')
        ->withPivot('ngayBatDau', 'ngayKetThuc', 'isDelete')
        ->withTimestamps();
    }
}
