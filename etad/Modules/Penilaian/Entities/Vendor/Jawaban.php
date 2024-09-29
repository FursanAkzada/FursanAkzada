<?php

namespace Modules\Penilaian\Entities\Vendor;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Pertanyaan\Vendor;
use Modules\Penilaian\Entities\Penilaian;

class Jawaban extends BaseModel
{
    use HasFactory;

    protected $table = 'trans_penilaian_vendor_jawaban';
    protected $fillable = [
        'penilaian_vendor_id',
        'pertanyaan_id',
        'value'
    ];

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class, 'penilaian_vendor_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Vendor::class, 'pertanyaan_id');
    }
}
