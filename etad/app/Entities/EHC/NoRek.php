<?php

namespace App\Entities\EHC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoRek extends Model
{
    use HasFactory;
    // protected $table = 'PYL_NO_REK';
    protected $table = 'ref_ehc_pyl_no_rek';
    public $timestamps = false;
    protected $fillable = [
        'Nip',
        'Astek',
        'NoRek',
        'user_nm',
        'dateCrea',
        'dateSystem',
        'NPWP',
    ];
}
