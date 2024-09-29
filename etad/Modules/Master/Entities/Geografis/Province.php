<?php

namespace Modules\Master\Entities\Geografis;

use App\Entities\BaseModel;

class Province extends BaseModel
{

    protected $table = 'ref_province';

    protected $fillable = [
        'name',
        'code',
    ];
    public function cities()
    {
        return $this->hasMany(City::class, 'province_id');
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->all());
            $record->save();
            //audit trail
            // $record->saveActivity([
            //     'title' => 'Parameter Geografis Provinsi',
            //     'description' => 'Create Parameter Geografis Provinsi ' . $record->name,
            // ]);
            auth()->user()->storeLog(
                'master.geografis.provinsi',
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
            //audit trail
            // $this->saveActivity([
            //     'title' => 'Parameter Geografis Provinsi',
            //     'description' => 'Edit Parameter Geografis Provinsi ' . $this->name,
            // ]);
            auth()->user()->storeLog(
                'master.geografis.provinsi',
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
                'master.geografis.provinsi',
                'delete',
            );
            $this->delete();
            //audit trail
            // $this->saveActivity([
            //     'title' => 'Parameter Geografis Provinsi',
            //     'description' => 'Delete Parameter Geografis Provinsi ' . $this->name,
            // ]);
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
