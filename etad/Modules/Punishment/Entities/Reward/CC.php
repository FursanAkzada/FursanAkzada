<?php

namespace Modules\punishment\Entities\Reward;

use App\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CC extends Model
{
    use HasFactory;

    protected $table = 'trans_reward_cc';
    protected $fillable = [
        'reward_id',
        'user_id',
        'read_at',
    ];

    public function reward()
    {
        return $this->belongsTo(Reward::class, 'reward_id');
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
            $this->reward->createLogs(
                'draft',
                'Menghapus Tembusan Penghargaan TAD: '.$this->user->name.' ('.$this->user->position_name.')'
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
            $this->reward->createLogs(
                'draft',
                'Memperbarui Tembusan Penghargaan TAD: '.$this->user->name.' ('.$this->user->position_name.')'
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
