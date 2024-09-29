<?php

namespace Modules\Master\Entities\Tad;

use App\Entities\BaseModel;
use App\Entities\Traits\HasFiles;
use App\Entities\SysFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WawancaraVendor extends BaseModel
{
    use HasFiles;

    protected $table = 'ref_tad_wawancara_vendor';
    protected $fillable = [
        'tad_id',
        'title',
    ];

    public function files()
    {
        return $this->hasMany(SysFile::class, 'target_id')
            ->where('target_type', Self::class);
    }

    public static function createByRequest($request)
    {
        $request->validate(
            [
                'title'     => ['required', 'unique_with:ref_tad_wawancara_vendor,title,tad_id'],
                'uploads.*'  => ['required'],
            ]
        );
        DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->all());
            $record->save();

            $record->saveFilesByTemp($request->uploads, 'pengajuan.tad.personil', 'wawancara-vendor');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
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
        $request->validate(
            [
                'title'     => ['required', 'unique_with:ref_tad_wawancara_vendor,title,tad_id,'.$this->id.'=id'],
            ]
        );
        DB::beginTransaction();
        try {
            $this->update($request->all());
            $this->saveFilesByTemp($request->uploads, 'pengajuan.tad.personil', 'wawancara-vendor');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
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

    public function deleteByRequest($request)
    {
        DB::beginTransaction();
        try {
            $this->files()->delete();
            $this->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
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
