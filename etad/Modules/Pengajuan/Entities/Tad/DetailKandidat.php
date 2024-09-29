<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Tad\Penerimaan;
use Modules\Pengajuan\Entities\Tad\SummaryKandidat;
use Modules\Pengajuan\Entities\Tad\SummaryWawancara;

class DetailKandidat extends BaseModel
{
    use HasFactory;

    protected $table = 'trans_pengajuan_tad_detailsummarykandidat';
    protected $fillable = [
        'summary_id',
        'tad_id',
        'status',
        'keterangan',
    ];

    protected $dates = ['accepted_at'];

    public function setAcceptedAtAttribute($value)
    {
        $this->attributes['accepted_at'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') :null;
    }

    public function penerimaan()
    {
        return $this->hasOneThrough(Penerimaan::class, SummaryWawancara::class, 'kandidat_id', 'wawancara_id');
    }

    public function employments()
    {
        return $this->hasMany(Kepegawaian::class, 'kandidat_id');
    }

    public function employment()
    {
        return $this->hasOne(Kepegawaian::class, 'kandidat_id');
    }

    public function summary()
    {
        return $this->belongsTo(SummaryKandidat::class,'summary_id');
    }

    public function tad()
    {
        return $this->belongsTo(TAD::class, 'tad_id');
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
