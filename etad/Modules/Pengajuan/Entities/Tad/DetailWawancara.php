<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Wawancara\Pertanyaan;
use Modules\Pengajuan\Entities\Tad\SummaryWawancara;

class DetailWawancara extends BaseModel
{
    use HasFactory;

    protected $table = 'trans_pengajuan_tad_detailwawancara';
    protected $fillable = [
        'summary_id',
        'pertanyaan_id',
        'value',
        'keterangan'
    ];

    protected $dates = ['accepted_at'];

    public function setAcceptedAtAttribute($value)
    {
        $this->attributes['accepted_at'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') :null;
    }

    public function wawancara()
    {
        return $this->belongsTo(SummaryWawancara::class,'summary_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class,'pertanyaan_id');
    }

    public function deleteByRequest()
    {
        $this->delete();
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfully deleted',
                'data'    => $this,
            ]
        );

    }

}
