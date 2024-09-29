<?php

namespace Modules\Master\Entities\Tad;

use App\Entities\BaseModel;
use App\Entities\EHC\Agama;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Geografis\City;

class Keluarga extends BaseModel
{
    use HasFactory;

    protected $table = 'ref_tad_keluarga';
    protected $fillable = [
        'tad_id',
        'tipe_id',
        'nama',
        'jenis_kelamin',
        'alamat',
        'telepon',
        'tempat_lahir',
        'tanggal_lahir',
        'agama_id',
        'kewarganegaraan',
        'urutan_anak',
    ];

    protected $dates = [
        'tanggal_lahir'
    ];

    public function setTanggalLahirAttribute($value)
    {
        if (gettype($value) === 'string') {
            $this->attributes['tanggal_lahir'] = $value ? Carbon::createFromFormat('d/m/Y', $value) : null;
        } elseif (gettype($value) === 'object') {
            $this->attributes['tanggal_lahir'] = Carbon::createFromFormat('d/m/Y', $value->format('d/m/Y'));
        }
    }

    public function getAttrJenisKelaminAttribute()
    {
        return ($this->attributes['jenis_kelamin'] == 'L') ? 'Laki - Laki' : 'Perempuan';
    }

    public function agama()
    {
        return $this->belongsTo(Agama::class, 'agama_id', 'Sandi');
    }

    public function tempatLahir()
    {
        return $this->belongsTo(City::class, 'tempat_lahir');
    }

    public function tipeKeluarga()
    {
        return $this->belongsTo(TipeKeluarga::class, 'tipe_id');
    }

    public function updateByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $this->update($request->all());
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
                'data'    => ''
            ]
        );
    }
}
