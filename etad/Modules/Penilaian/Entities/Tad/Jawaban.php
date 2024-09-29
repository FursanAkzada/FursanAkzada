<?php

namespace Modules\Penilaian\Entities\Tad;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Wawancara\Pertanyaan;

class Jawaban extends BaseModel
{
    use HasFactory;

    protected $table = 'trans_penilaian_tad_jawaban';
    protected $fillable = [
        'penilaian_id',
        'pertanyaan_id',
        'value',
        'penilai',
    ];

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class, 'penilaian_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }
}
