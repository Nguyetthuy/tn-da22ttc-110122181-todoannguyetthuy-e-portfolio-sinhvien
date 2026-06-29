<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class khoaTuyenSinh extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table='khoa_tuyensinh';
    protected $primaryKey = 'maKhoaTuyenSinh';
    protected $fillable = ['maKhoaTuyenSinh','namTS','isDelete'];
    
}
