<?php

namespace Modules\Pengajuan\Entities\Tad\Wawancara;

use App\Entities\BaseModel;
use App\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Tad\Kandidat;
use Modules\Pengajuan\Entities\Tad\Pengajuan;

class Wawancara extends BaseModel
{
    use HasFactory;

    protected $table = 'trans_pengajuan_tad_wawancara';
    protected $fillable = [
        'kandidat_id',
        'tgl',
        'pewawancara',
        'kesimpulan',
        'saran',
    ];

    protected $casts = [
        'tgl'       =>  'date',
    ];

    public function getCreatedAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'wawancara_id');
    }

    public function kandidat()
    {
        return $this->belongsTo(Kandidat::class, 'kandidat_id');
    }

    public function kesimpulanRaw()
    {
        switch ($this->kesimpulan) {
            case '1':
                return '<span class="badge badge-success">Sangat Disarankan</span>';
            case '2':
                return '<span class="badge badge-success">Disarankan</span>';
            case '3':
                return '<span class="badge badge-warning">Cukup Disarankan</span>';
            case '4':
                return '<span class="badge badge-danger">Kurang Disarankan</span>';
            case '5':
                return '<span class="badge badge-danger">Tidak Disarankan</span>';
            case '6':
                return '<span class="badge badge-danger">Tidak Hadir</span>';
            default:
                return '<span class="badge badge-warning text-white">Belum Di Wawancara</span>';
                break;
        }
    }

    public function interviewers()
    {
        return $this->belongsToMany(
            User::class,
            'trans_pengajuan_tad_pewawancara',
            'wawancara_id',
            'user_id'
        );
    }

    public function scopeOngoing($query, $kandidat)
    {
        return $query->where('kandidat_id', $kandidat);
    }

    public function updateByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $this->update($request->except('question'));
            foreach ($request->question as $key => $item) {
                $penilaian = Penilaian::find($key);
                $penilaian->fill(
                    [
                        'value' => $item
                    ]
                );
                $penilaian->save();
            }
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
                'message' => 'Data successfuly updated',
                'data'    => $this
            ]
        );
    }
}
