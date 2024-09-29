<?php

namespace Modules\Master\Entities;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Tad\Tad;

class Pendidikan extends BaseModel
{
    use HasFactory;

    protected $table = 'ref_pendidikan';
    protected $fillable = [
        'name',
        'description'
    ];

    public function jurusans()
    {
        return $this->hasMany(Jurusan::class, 'pendidikan_id');
    }

    public function tad()
    {
        return $this->hasMany(Tad::class, 'pendidikan_id');
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->all());
            $record->save();
            auth()->user()->storeLog(
                'master.pendidikan',
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
                'master.pendidikan',
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
                'master.pendidikan',
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
        if ($this->jurusans()->count()) return false;
        if ($this->tad()->count()) return false;
        return true;
    }
}
