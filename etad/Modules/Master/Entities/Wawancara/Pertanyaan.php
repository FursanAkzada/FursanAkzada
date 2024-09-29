<?php

namespace Modules\Master\Entities\Wawancara;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Pengajuan\Entities\Tad\DetailWawancara;


class Pertanyaan extends BaseModel
{
    use HasFactory;

    protected $table = 'ref_wawancara_pertanyaan';
    protected $fillable = [
        'kompetensi_id',
        'pertanyaan'
    ];

    public function kompetensi()
    {
        return $this->belongsTo(Kompetensi::class, 'kompetensi_id');
    }

    public function detailPertanyaanWawancara()
    {
        return $this->hasMany(DetailWawancara::class, 'pertanyaan_id');
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->all());
            $record->save();
            auth()->user()->storeLog(
                'master.wawancara.pertanyaan',
                'create',
            );
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data successfully saved',
            'data'    => $record
        ]);
    }

    public function updateByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $this->update($request->all());
            auth()->user()->storeLog(
                'master.wawancara.pertanyaan',
                'update',
            );
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data successfuly updated',
            'data'    => $this
        ]);
    }

    public function deleteByRequest()
    {
        \DB::beginTransaction();
        try {
            $this->delete();
            auth()->user()->storeLog(
                'master.wawancara.pertanyaan',
                'delete',
            );
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data successfully deleted',
            'data'    => $this
        ]);
    }

    public function canDeleted()
    {
        if ($this->detailPertanyaanWawancara()->count()) return false;

        return true;
    }
}
