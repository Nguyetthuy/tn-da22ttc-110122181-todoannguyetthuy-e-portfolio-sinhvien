<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class khoa_tuyensinh_ctdt extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table='khoa_tuyensinh_ct_daotao';
    protected $primaryKey = 'id';
    protected $fillable = ['maKhoaTuyenSinh','maCT','maCDR_CTDT','isDelete'];
    
}
