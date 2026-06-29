<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    use HasFactory;

    protected $table='users';

    protected $primaryKey = 'username';
    public $incrementing = false;
    protected $fillable = ['username','email','password','permission','isBlock','isDelete'];
}
