<?php

namespace Modules\Pengajuan\Entities\Mutasi;

use App\Entities\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;

class Pegawai extends BaseModel
{
    use HasFactory;

    protected $table    = 'trans_pengajuan_mutasi_pegawai';
    protected $fillable = [
        'pengajuan_id',
        'tad_id',
        'kepegawaian_id',
        'tgl_mutasi',
        'tgl_efektif',
        'synced',
    ];
    protected $dates    = [
        'tgl_mutasi',
        'tgl_efektif',
    ];

    public function setTglEfektifAttribute($value)
    {
        $this->attributes['tgl_efektif'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') :null;
    }
    public function setTglMutasiAttribute($value)
    {
        $this->attributes['tgl_mutasi'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') :null;
    }

    function kepegawaian() {
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
