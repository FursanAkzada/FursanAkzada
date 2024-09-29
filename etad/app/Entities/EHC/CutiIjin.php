<?php

namespace App\Entities\EHC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CutiIjin extends Model
{
    use HasFactory;
    // protected $table = 'PYL_PJUMLAH_CUTI_IJIN';
    protected $table = 'ref_ehc_pjumlah_cuti_ijin';
    public $timestamps = false;
    protected $fillable = [
        'nip',
        'jumlah_tahunan',
        'jumlah_haji',
        'jumlah_melahirkan',
        'jml_pend',
        'jml_extra',
        'jml_ctbesar',
    ];
}
