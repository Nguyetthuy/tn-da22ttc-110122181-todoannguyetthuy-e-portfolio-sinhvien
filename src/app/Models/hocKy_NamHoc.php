<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hocky_namhoc extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table='hocky_namhoc';
    protected $primaryKey = 'maHKNH';
    protected $fillable = ['maHKNH','tenHKNH','isDelete'];
    
}
