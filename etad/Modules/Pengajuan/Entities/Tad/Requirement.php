<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use App\Entities\EHC\Jabatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Vendor;

class Requirement extends BaseModel
{
    use HasFactory;

    protected $table = 'trans_pengajuan_tad_requirement';
    protected $fillable = [
        'pengajuan_id',
        'jabatan_id',
        'jenis_kelamin',
        'vendor_id',
        'jumlah',
        'flag'
    ];

    protected $casts = [
        'jumlah' => 'int'
    ];

    public function scopeProcess($query)
    {
        return $query->where('flag', 'process');
    }

    public function scopeClose($query)
    {
        return $query->where('flag', 'close');
    }

    public function scopeCurVendor($query)
    {
        return $query->where('vendor_id', auth()->user()->vendor_id);
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'idunit');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function kandidats()
    {
        return $this->hasMany(Kandidat::class, 'requirement_id');
    }

    public function summaryKandidat()
    {
        return $this->hasOne(SummaryKandidat::class, 'requirement_id');
    }

    public function kandidat()
    {
        return $this->belongsToMany(Tad::class, Kandidat::class, 'requirement_id', 'tad_id')
            ->withPivot(
                ['id', 'accepted', 'alasan', 'accepted_at', 'accepted_by']
            );
    }

    public function scopeReprocessVendor($query)
    {
        return $query->whereHas(
            'kandidats',
            function ($query) {
            }
        );
    }

    public function kandidatByRequest($request)
    {
        \DB::beginTransaction();
        try {
            // if(count($request->kandidat_id) < $request->jumlah_requirement){
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Jumlah kandidat terpilih masih kurang dari jumlah yg dibutuhkan',
            //     ],422);
            // }
            $user = auth()->user();
            $this->kandidat()->sync($request->kandidat_id);
            $this->pengajuan->pivotTad()->sync($request->kandidat_id);
            $this->pengajuan->createLogs('vendor.kandidat', 'Vendor '.$user->vendor->nama.' Mengubah Kandidat', 0);
            auth()->user()->storeLog('pengajuan.tad', 'kandidat', json_decode($this->pengajuan), json_decode($this->pengajuan));
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
}
