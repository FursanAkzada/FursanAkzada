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
use Modules\Pengajuan\Entities\Tad\Kandidat;
use Modules\Penilaian\Entities\Tad\Penilaian;
use Modules\Punishment\Entities\Pembinaan\Pembinaan;


class KepegawaianFailed extends Kepegawaian
{
    use HasFactory;

    protected $table = 'ref_tad_kepegawaian_failed';

    public function tad()
    {
        return $this->belongsTo(TadFailed::class, 'tad_id');
    }
}
