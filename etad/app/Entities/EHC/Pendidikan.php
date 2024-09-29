<?php

namespace App\Entities\EHC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendidikan extends Model
{
    use HasFactory;
    // protected $table = 'SEKOLAH';
    protected $table = 'ref_ehc_sekolah';
    protected $fillable = [
        'sandi',
        'lengkap'
    ];
    public $timestamps = false;
}
