<?php

namespace App\Entities\EHC;

use App\Entities\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\KategoriVendor;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Tad\Quota;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;

class Jabatan extends BaseModel
{
    use HasFactory;
    // protected $table = 'TBL_UNITKERJA';
    protected $table = 'ref_ehc_unit_kerja';
    protected $fillable = [
        'idunit',
        'NM_UNIT',
        'jenis',
        'kategori_id',
        'is_migrasi'
    ];
    protected $primaryKey = 'idunit';
    // protected $dates = ['created_at', 'updated_at'];

    public $timestamps = true;

    protected $module = 'master.jabatan-tad';

    public function kategori()
    {
        return $this->belongsTo(KategoriVendor::class, 'kategori_id');
    }

    public function personils()
    {
        return $this->hasMany(Tad::class, 'jabatan_id');
    }

    public function quotas()
    {
        return $this->hasMany(Quota::class, 'posisi_tad_id');
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            // $urut = PertanyaanTad::where('parent_id', $request->parent_id)->orderBy('urut', 'desc')->first();
            // $record->urut = $urut ? $urut->urut + 1 : 0;
            $record = new Self;
            $record->fill($request->all());
            $record->idunit = Self::max('idunit') + 1;
            $record->NM_UNIT = $request->name;
            $record->save();

            // $QUOTA_PERIODE = QuotaPeriode::get();
            // $ORG_STRUCTS = OrgStruct::get();
            // foreach ($QUOTA_PERIODE as $key => $periode) {
            //     if ($periode->status != 'draft') {
            //         $periode->status = 'new-position';
            //         $periode->save();
            //     }
            //     foreach ($ORG_STRUCTS as $key => $org_struct) {
            //         $quota = new Quota;
            //         $quota->status = 'new';
            //         $quota->pengajuan_tad_quota_periode_id  = $periode->id;
            //         $quota->org_struct_id                   = $org_struct->id;
            //         $quota->posisi_tad_id                   = $record->id;
            //         $quota->quota = 0;
            //         $quota->save();
            //     }
            // }

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
        try {
            $data = $request->all();
            $data['NM_UNIT'] = $request->name;
            $this->update($data);

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

    public function canDelete()
    {
        return !($this->quotas()->exists() ||
            $this->personils()->exists()
        );
    }
}
