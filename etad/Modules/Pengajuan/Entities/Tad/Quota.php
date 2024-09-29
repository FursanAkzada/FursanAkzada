<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use App\Entities\EHC\Jabatan;
use App\Entities\User;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Tad\Pengajuan;

class Quota extends BaseModel
{
    protected $table = 'trans_pengajuan_tad_quota';
    protected $fillable = [
        'status',
        'pengajuan_tad_quota_periode_id',
        'org_struct_id',
        'posisi_tad_id',
        'quota',
    ];

    // protected $module = 'pengajuan.tad.quota.detail';
    protected $appends = [
        // 'used',
        // 'available',
        // 'dipakai',
    ];

    public function periode()
    {
        return $this->belongsTo(QuotaPeriode::class, 'pengajuan_tad_quota_periode_id');
    }

    public function posisi()
    {
        return $this->belongsTo(Jabatan::class, 'posisi_tad_id');
    }

    public function so()
    {
        return $this->belongsTo(OrgStruct::class, 'org_struct_id');
    }

    public function canDelete()
    {
        return true;
        // return !($this->vendors()->exists());
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->all());
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
        try {
            $this->update($request->all());
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
            if ($this->periode === 'approved') {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Data cannot be deleted, Periode is approved',
                        'data'    => $this
                    ]
                );
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

    public function getUsedAttribute()
    {
        // \DB::connection()->enableQueryLog();
        $jumlah = [
            'mutation'  => 0,
            'WORK'      => 0,
        ];
        $EMPLOYMENTS = Kepegawaian::select('id')
            ->where('year', $this->periode->year)
            ->where('semester', $this->periode->semester)
            ->whereIn('status', [
                Kepegawaian::WORK,
                Kepegawaian::MUTATION,
                Kepegawaian::RECRUITMENT,
                Kepegawaian::MIGRATE
            ])
            ->where('cabang_id', $this->org_struct_id)
            ->where('jabatan_id', $this->posisi_tad_id)
            ->get();
        // if ($this->posisi_tad_id == 7 && $this->org_struct_id == 28) {
        //     // dd(208, json_decode($EMPLOYMENTS));
        // }
        foreach ($EMPLOYMENTS as $key => $employment) {
            $jumlah['WORK'] += 1;
        }

        $jumlah['total'] = array_sum($jumlah);
        return $jumlah;
    }

    public function getAvailableAttribute()
    {
        return ($this->quota - (($this->used['total'] ?? 0) - ($this->used['WORK'] ?? 0)) ?? 0) ?? 0;
    }

    public function getDipakaiAttribute()
    {
        return $this->used['WORK'] ?? 0;
    }

    // http://etad.me/dev/quota/available?year=2022&semester=Satu&org_struct_id=10&posisi_tad_id=4
    public static function countAvailable($year, $semester, $level, $org_struct_id = null, $posisi_tad_id = null)
    {
        $quota = Quota::whereHas(
            'periode',
            function ($q) use ($year, $semester) {
                $q
                    // ->where('status', 'completed')
                    ->where('year', $year)
                    ->where('semester', $semester);
            }
        )
            ->when(
                $org_struct_id,
                function ($q) use ($org_struct_id) {
                    $q->where('org_struct_id', $org_struct_id);
                }
            )
            ->when(
                $posisi_tad_id,
                function ($q) use ($posisi_tad_id) {
                    $q->where('posisi_tad_id', $posisi_tad_id);
                }
            )
            // ->with('posisi')
            ->first();
        // dd(201, $year, $semester, $org_struct_id, $posisi_tad_id, json_decode($quota));
        if ($quota) {
            return $quota->quota - $quota->fulfillment;
        }
        return 0;
    }
}
