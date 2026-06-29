<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CDR_CTDT extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table='cdr_ctdt';
    protected $primaryKey = 'maCDR_CTDT';
    protected $fillable = ['maCDR_CTDT','maCDR_CTDT_VB','tenCDR_CTDT','isDelete','maCT'];

    
}
