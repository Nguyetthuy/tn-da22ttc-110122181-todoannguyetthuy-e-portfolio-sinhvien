<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ctDaoTao extends Model
{
    use HasFactory;
    protected $table='ct_dao_tao';
    protected $primaryKey='maCT';
    public $incrementing=false;
    protected $fillable = ['tenCT','maBac','maCNganh','maHe','maBM','soQuyetDinh','ngayBanHanh','isDelete'];

    public function bac()
    {
        return $this->hasOne('App\Models\bacDaoTao', 'maBac', 'maBac');
    }

    public function cnganh()
    {
        return $this->hasOne('App\Models\cNganh', 'maCNganh', 'maCNganh');
    }

    public function he()
    {
        return $this->hasOne('App\Models\he', 'maHe', 'maHe');
    }
    public function boMon()
    {
        return $this->hasOne('App\Models\boMon', 'maBM', 'maBM');
    }
    
    public function hocphan()
    {
        return $this->hasOne('App\Models\hocPhan_ctDaoTao','maHocPhan', 'maHocPhan');
    }
}
