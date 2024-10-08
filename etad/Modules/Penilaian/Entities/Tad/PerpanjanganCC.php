<?php

namespace Modules\Penilaian\Entities\Tad;

use App\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerpanjanganCC extends Model
{
    use HasFactory;

    protected $table = 'trans_perpanjangan_tad_cc';
    protected $fillable = [
        'pengajuan_id',
        'user_id',
        'read_at',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Perpanjangan::class, 'pengajuan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function deleteByRequest()
    {
        \DB::beginTransaction();
        try {
            $this->delete();
            // Save Log
            $this->pengajuan->createLogs(
                'draft',
                'Menghapus Tembusan Perpanjangan TAD: '.$this->user->name.' ('.$this->user->position_name.')'
            );
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

    public function ccUpdateByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $this->update($request->all());
            // Save Log
            $this->pengajuan->createLogs(
                'draft',
                'Memperbarui Tembusan Perpanjangan TAD: '.$this->user->name.' ('.$this->user->position_name.')'
            );
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
