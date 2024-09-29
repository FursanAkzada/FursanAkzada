<?php

namespace App\Entities\EHC;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Modules\Punishment\Entities\Pembinaan\Pembinaan;
use Modules\Punishment\Entities\Reward\Reward;

class JenisPunishment extends BaseModel
{
    use HasFactory;
    // protected $table = 'Jenis_Reward';
    protected $table = 'ref_ehc_jenis_reward';
    protected $primaryKey = 'sandi';
    protected $fillable = [
        'sandi',
        'Lengkap',
        'description',
    ];
    protected $module = null;

    public function scopePunishment($query)
    {
        return $query->where('sandi', 'LIKE', '20%');
    }
    public function scopePembinaan($query)
    {
        return $query->where('sandi', 'LIKE', '3%');
    }
    public function scopeReward($query)
    {
        return $query->where('sandi', 'LIKE', '1%');
    }

    public function TPembinaan()
    {
        return $this->hasMany(Pembinaan::class, 'jenis_id');
    }
    public function TReward()
    {
        return $this->hasMany(Reward::class, 'jenis_id');
    }

    public function canDelete()
    {
        return !($this->TPembinaan()->exists() || $this->TReward()->exists());
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        $request->validate(
            [
                'Lengkap' => ['required', 'unique:ref_ehc_jenis_reward,Lengkap']
            ]
        );
        try {
            $record = new Self;
            $record->fill($request->all());
            if ($request->type == 'reward') {
                $record->module = 'master.rp.reward';
                $REWARD = \App\Entities\EHC\JenisPunishment::reward()
                    ->orderBy('sandi', 'DESC')
                    ->first();
                $record->sandi = (string)(($REWARD->sandi ?? 1000) + 1);
            } elseif ($request->type == 'pembinaan') {
                $record->module = 'master.rp.pembinaan';
                $PEMBINAAN = \App\Entities\EHC\JenisPunishment::pembinaan()
                    ->orderBy('sandi', 'DESC')
                    ->first();
                $record->sandi = (string)(($PEMBINAAN->sandi ?? 3000) + 1);
            }
            $record->save();
            \DB::commit();
        } catch (\Throwable $e) {
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

    public function updateByRequest($request)
    {
        \DB::beginTransaction();
        $request->validate(
            [
                'Lengkap' => ['required', Rule::unique('ref_ehc_jenis_reward', 'Lengkap')->ignore($this->sandi, 'sandi'),]
            ]
        );
        try {
            if ($request->type == 'pembinaan') {
                $this->module = 'master.rp.pembinaan';
            } elseif ($request->type == 'reward') {
                $this->module = 'master.rp.reward';
            }
            $this->fill($request->all());
            $this->save();
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
            $sandi = ((string)$this->sandi)[0] ?? '';
            if ($sandi == 3) {
                $this->module = 'master.rp.pembinaan';
            } elseif ($sandi == 1) {
                $this->module = 'master.rp.reward';
            }
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
