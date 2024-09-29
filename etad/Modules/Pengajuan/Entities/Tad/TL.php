<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TL extends BaseModel
{
    use HasFactory;

    protected $table = 'trans_pengajuan_tad_tl';
    protected $fillable = [
        'pengajuan_id',
        'no_surat',
        'surat_filename',
        'surat_filepath',
        'batch'
    ];

    public function pengajuan()
    {
        return $this->belongsTo(
            Pengajuan::class,
            'pengajuan_id'
        );
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->all());
            $record->save();
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
                'message' => 'Data successfully saved',
                'data'    => $record
            ]
        );
    }
}
