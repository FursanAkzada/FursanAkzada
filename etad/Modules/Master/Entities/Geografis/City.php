<?php

namespace Modules\Master\Entities\Geografis;

use App\Entities\BaseModel;

class City extends BaseModel
{
    public $table = 'ref_city';
    protected $fillable = [
        'province_id', 'name', 'code'
    ];

    /* Relation */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }


    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->all());
            $record->save();

            auth()->user()->storeLog(
                'master.geografis.kab-kota',
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
                'master.geografis.kab-kota',
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
            auth()->user()->storeLog(
                'master.geografis.kab-kota',
                'delete',
            );
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
