<?php

namespace Modules\Pengajuan\Entities\Tad\Wawancara;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Wawancara\Pertanyaan;

class Penilaian extends BaseModel
{
    use HasFactory;

    protected $table = 'trans_pengajuan_tad_wawancara_penilaian';
    protected $fillable = [
        'wawancara_id',
        'pertanyaan_id',
        'value',
    ];

    public function wawancara()
    {
        return $this->belongsTo(Wawancara::class,'wawancara_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class,'pertanyaan_id');
    }
}
