<?php

namespace App\Entities\EHC;

use App\Entities\EHC\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;
    // protected $table = 'M_LOGIN';
    protected $table = 'sys_ehc_m_login';
    public $timestamps = false;

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'DIVISI', 'KDCAB');
    }
}
