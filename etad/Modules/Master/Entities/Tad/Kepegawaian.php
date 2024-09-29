<?php

namespace Modules\Master\Entities\Tad;

use App\Entities\BaseModel;
use App\Entities\EHC\Jabatan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Entities\Mutasi\Pegawai;
use Modules\Pengajuan\Entities\Resign\Pegawai as ResignPegawai;
use Modules\Pengajuan\Entities\Tad\Penerimaan;
use Modules\Penilaian\Entities\Tad\Penilaian;
use Modules\Punishment\Entities\Pembinaan\Pembinaan;

class Kepegawaian extends BaseModel
{
    use HasFactory;

    protected $table = 'ref_tad_kepegawaian';
    protected $fillable = [
        'status',
        'previous_employment_id',
        'is_imported',
        'year',
        'semester',
        'kandidat_id',
        'tad_id',
        'vendor_id',
        'jabatan_id',
        'nio',
        'no_sk',
        'cabang_id',
        'pengajuan_mutasi_pegawai_id',
        'pengajuan_resign_pegawai_id',
        'penghargaan_id',
        'pembinaan_id',
        'penilaian_id',
        'in_at',
        'mutation_at',
        'resign_at',
        'out_at',
        'contract_due',
        'jenis_jabatan',
    ];

    protected $casts = [
        'contract_due'  => 'date',
        'in_at'         => 'date',
        'mutation_at'   => 'date',
        'resign_at'     => 'date',
        'out_at'        => 'date',
    ];

    const WORK          = 1;
    const RESIGN        = 2;
    const END           = 3;
    const MUTATION      = 4;
    const RECRUITMENT   = 5;
    const EXTENSION     = 6;
    const MIGRATE       = 7;
    const STATUS = [
        Self::WORK          => 'Bekerja',
        Self::RESIGN        => 'Resign',
        Self::END           => 'Habis Kontrak',
        Self::MUTATION      => 'Mutasi',
        Self::RECRUITMENT   => 'Penerimaan',
        Self::EXTENSION     => 'Perpanjangan Kontrak',
        Self::MIGRATE       => 'Migrasi',
    ];

    public function setContractDueAttribute($value)
    {
        $this->attributes['contract_due'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }
    public function setInAtAttribute($value)
    {
        $this->attributes['in_at'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }
    public function setMutationAtAttribute($value)
    {
        $this->attributes['mutation_at'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }
    public function setResignAtAttribute($value)
    {
        $this->attributes['resign_at'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }
    public function setOutAtAttribute($value)
    {
        if ($value) {
            try {
                $this->attributes['out_at'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
            } catch (\Throwable $th) {
            }
        }
    }

    public function mutationEmployment()
    {
        return $this->hasOne(Self::class, 'previous_employment_id', 'id');
    }
    public function previousEmployment()
    {
        return $this->belongsTo(Self::class, 'previous_employment_id', 'id');
    }
    public function pengajuanMutasiPegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pengajuan_mutasi_pegawai_id');
    }
    public function pengajuanResignPegawai()
    {
        return $this->belongsTo(ResignPegawai::class, 'pengajuan_resign_pegawai_id');
    }
    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class, 'penilaian_id');
    }
    public function pembinaan()
    {
        return $this->belongsTo(Pembinaan::class, 'pembinaan_id');
    }
    public function penghargaan()
    {
        return $this->belongsTo(Penghargaan::class, 'penghargaan_id');
    }
    public function kandidat()
    {
        return $this->belongsTo(Penerimaan::class, 'kandidat_id');
    }

    public function tad()
    {
        return $this->belongsTo(Tad::class, 'tad_id');
    }

    public function unitKerja()
    {
        return $this->belongsTo(OrgStruct::class, 'cabang_id');
    }

    public function unit_kerja()
    {
        return $this->belongsTo(OrgStruct::class, 'cabang_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
}
