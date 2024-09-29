<?php

namespace Modules\Master\Entities\SO;

use App\Entities\BaseModel;
use App\Entities\Role;

class Unit extends BaseModel
{

    protected $table = 'ref_unit';
    protected $fillable = [
        'code',
        'name',
        'mailing',
        'description',
        'is_active'
    ];

    public function jabatan()
    {
        $this->hasMany(Role::class,'unit_id');
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->all());
            $record->save();
            
            auth()->user()->storeLog(
                'master.so.root',
                'create',
                $record->toArray(),
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
}
