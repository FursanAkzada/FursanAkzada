<?php

namespace Modules\Pengajuan\Entities\Resign;

use App\Entities\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Resign\Reason;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;


class Pegawai extends BaseModel
{
    use HasFactory;

    protected $table    = 'trans_pengajuan_resign_pegawai';
    protected $fillable = [
        'pengajuan_id',
        'tad_id',
        'kepegawaian_id',
        'alasan',
        'tanggal_resign',
        'tanggal_efektif',
        'synced',
    ];
    protected $dates    = [
        'tanggal_resign',
        'tanggal_efektif'
    ];

    public function setTanggalEfektifAttribute($value)
    {
        $this->attributes['tanggal_efektif'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }
    public function setTanggalResignAttribute($value)
    {
        $this->attributes['tanggal_resign'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }

    public function kepegawaian()
    {
        return $this->belongsTo(Kepegawaian::class, 'kepegawaian_id');
    }
    public function tad()
    {
        return $this->belongsTo(Tad::class, 'tad_id');
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function alasan_resign()
    {
        return $this->belongsTo(Reason::class, 'alasan');
    }

    public function deleteByRequest()
    {
        \DB::beginTransaction();
        try {
            $this->delete();
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfully deleted',
                'data'    => $this
            ]
        );
    }
}
