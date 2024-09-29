<?php

namespace App\Entities\EHC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agama extends Model
{
    use HasFactory;
    // protected $table = 'AGAMA';
    protected $table = 'ref_ehc_agama';
    public $timestamps = false;
    protected $fillable = [
        "Lengkap",
        "Sandi"
    ];
}
