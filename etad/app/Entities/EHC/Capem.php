<?php

namespace App\Entities\EHC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capem extends Model
{
    use HasFactory;
    // protected $table = 'CABCPM';
    protected $table = 'ref_ehc_capem';
    public $timestamps = false;
    protected $fillable = [
        "KDCAB",
        "CABANG",
        "KDCAPEM",
        "CAPEM",
        "STATCAPEM",
    ];
}
