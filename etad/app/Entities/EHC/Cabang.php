<?php

namespace App\Entities\EHC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;
    // protected $table = 'CABANG';
    protected $table = 'ref_ehc_cabang';
    public $timestamps = false;
    protected $fillable = [
        "Sandi",
        "Lengkap",
        "usrCrea",
        "usrTime",
        "statusRec",
    ];
}
