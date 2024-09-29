<?php

namespace App\Entities\EHC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;
    // protected $table = 'DIVISI';
    protected $table = 'ref_ehc_divisi';
    public $timestamps = false;

    protected $fillable = [
        'Sandi',
        'Lengkap',
        'STAT_KANTOR',
        'kelas',
        'limit',
        'kd_ojk',
    ];
}
