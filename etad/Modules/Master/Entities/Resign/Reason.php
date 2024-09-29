<?php

namespace Modules\Master\Entities\Resign;

use App\Entities\BaseModel;
use App\Entities\Role;
use Modules\Pengajuan\Entities\Resign\Pegawai;
use Modules\Pengajuan\Entities\Resign\Pengajuan;

class Reason extends BaseModel
{

    protected $table = 'ref_alasan_resign';
    protected $fillable = [
        'alasan',
        'description',
    ];

    public function pengajuan_resign()
    {
        return $this->hasMany(Pegawai::class, 'alasan');
    }

    public static function handleCreateByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->all());
            $record->save();
            auth()->user()->storeLog(
                'master.reason-resign',
                'create',
            );
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

    public function handleUpdateByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $this->update($request->all());
            auth()->user()->storeLog(
                'master.reason-resign',
                'update',
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

    public function canDelete()
    {
        return !($this->pengajuan_resign()->exists());
    }

    public function deleteByRequest()
    {
        \DB::beginTransaction();
        try {
            $this->delete();
            auth()->user()->storeLog(
                'master.reason-resign',
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

    public static function selectAjax($limit = 32)
    {
        $records = Self::orderBy('alasan', 'asc')->paginate($limit);

        $response = [];
        foreach ($records as $key => $value) {
            $response['results'][] = [
                'id' => $value->id,
                'text' => $value->alasan,
            ];
        }
        $response['more'] = $records->hasMorePages();
        return response()->json($response, 200);
    }
}
