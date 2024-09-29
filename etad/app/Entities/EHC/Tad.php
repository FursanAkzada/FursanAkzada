<?php

namespace App\Entities\EHC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tad extends Model
{
    // protected $table = 'TBL_DAPEG_OUTSOURCING';
    protected $table = 'ref_ehc_dapeg_outsourcing';
    protected $dates = [
        'TGL_LAHIR',
        'TGL_MASUK',
        'TGL_PERSETUJUAN',
    ];

    protected $fillable = [
        'NO',
        'NAMA',
        'NIO',
        'CABANG',
        'UNIT_KERJA',
        'JENIS_JABATAN',
        'ALAMAT',
        'KOTA',
        'TGL_MASUK',
        'BLN_BERAKHIR',
        'TGL_LAHIR',
        'TEMPAT_LAHIR',
        'STAT_AKTIF',
        'JNS_KELAMIN',
        'STAT_KAWIN',
        'NAMA_PERUSAHAAN',
        'NO_SK',
        'PENDIDIKAN',
        'REKENING',
        'STKANTOR',
        'TGL_PERSETUJUAN',
        'PENEMPATAN',
        'KETERANGAN',
        'AGAMA',
        'NOHP',
    ];

    public $timestamps = false;
}
