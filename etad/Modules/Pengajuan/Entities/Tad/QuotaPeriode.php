<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use App\Entities\EHC\Jabatan;
use App\Entities\Traits\Approval;
use App\Entities\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Pengajuan\Notifications\Notify;

class QuotaPeriode extends BaseModel
{
    use HasFactory, Approval;

    protected $table = 'trans_pengajuan_tad_quota_periode';
    protected $fillable = [
        'version',
        'status',
        'year',
        'semester',
        'level',
        'upgrade_reason',
        'quota',
        'quota_lama',
        'fulfillment',
    ];

    public function activeLogs()
    {
        return $this->hasOne(QuotaLog::class, 'pengajuan_tad_quota_periode_id')->where('is_active', 1);
    }
    public function logs()
    {
        return $this->hasMany(QuotaLog::class, 'pengajuan_tad_quota_periode_id');
    }

    public function posisi()
    {
        return $this->belongsTo(Jabatan::class, 'posisi_tad_id');
    }

    public function quotas()
    {
        return $this->hasMany(Quota::class, 'pengajuan_tad_quota_periode_id');
    }


    function getLevelShowAttribute()
    {
        return [
            'bod'           =>  'Direksi',
            'vice'          =>  'SEVP',
            'division'      =>  'Divisi',
            'departemen'    =>  'Sub Divisi',
            'cabang'        =>  'Cabang',
            'capem'         =>  'Cabang Pembantu',
            'kas'           =>  'Kantor Kas',
        ][$this->level] ?? $this->level;
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            if(!Self::where('status', 'completed')->orWhere('version', '>', 0)->get()->first()){
                return response()->json(
                    [
                        'success'   => false,
                        'message'   => 'Lakukan migrasi quota terlebih dahulu!',
                        'trace'     => null,
                    ],
                    422
                );
            }
            $record = new Self;
            $record->fill($request->all());
            $record->save();
            $record->createLogs('logs', 'Membuat Periode Baru');
            auth()->user()->storeLog($request->module, 'create');
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
    }

    public function updateByRequest($request)
    {
        \DB::beginTransaction();
        // dd(json_decode($this), $request->detail, $request->is_submit, $request->save_submit, $request->all());
        try {
            if(!Self::where('status', 'completed')->orWhere('version', '>', 0)->get()->first()){
                return response()->json(
                    [
                        'success'   => false,
                        'message'   => 'Lakukan migrasi quota terlebih dahulu!',
                        'trace'     => null,
                    ],
                    422
                );
            }

            if ($request->detail == 1) {
                foreach ($request->quota as $org_struct => $posisi_tads) {
                    foreach ($posisi_tads as $posisi_tad => $QUOTA) {
                        $quota = Quota::firstOrNew(
                            [
                                'pengajuan_tad_quota_periode_id'    => $this->id,
                                'org_struct_id'                     => $org_struct,
                                'posisi_tad_id'                     => $posisi_tad
                            ]
                        );
                        if ($quota->quota < $quota->fulfillment) {
                            return response()->json(
                                [
                                    'success'   => false,
                                    'message'   => 'Quota di Unit Kerja ' . $quota->so->name . ' Posisi ' . $quota->posisi->NM_UNIT . ' kurang dari jumlah pemenuhan saat ini!',
                                    'trace'     => null,
                                    'data'      => $quota,
                                ],
                                422
                            );
                        } else {
                            $quota->quota = $QUOTA['quota'] ?? 0;
                            $quota->status = $request->save_submit == 1 ? 'submit' : 'draft';
                            $quota->save();
                        }
                    }
                }
                $this->status   = $request->save_submit == 1 ? 'submit' : 'draft';
                $this->quota_lama    = $this->quotas()->sum('quota');
                $this->save();
                if ($request->save_submit == 1) {
                    $this->generateApproval($request->module);

                    $this->saveLogNotifyApproval($request);
                    $this->createLogs('submit', 'Submit Periode Quota');
                    auth()->user()->storeLog($request->module, 'submit');
                } else {
                    $this->createLogs('draft', 'Menyimpan Sebagai Draft');
                    auth()->user()->storeLog($request->module, 'update');
                }
            } else {
                $this->update($request->all());
                $this->createLogs('logs', 'Mengubah Periode Quota');
                auth()->user()->storeLog($request->module, 'update');
            }
            // QuotaPeriode::adjustQuotaByFulfillment();
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
                'data'    => $this,
                'redirect' => route($request->route . '.index'),
            ]
        );
    }

    public function deleteByRequest($request)
    {
        \DB::beginTransaction();
        try {
            if(!Self::where('status', 'completed')->orWhere('version', '>', 0)->get()->first()){
                return response()->json(
                    [
                        'success'   => false,
                        'message'   => 'Lakukan migrasi quota terlebih dahulu!',
                        'trace'     => null,
                    ],
                    422
                );
            }

            $this->logs()->delete();
            $this->quotas()->delete();
            $this->delete();
            auth()->user()->storeLog($request->module, 'delete');
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
                'data'    => $this,
            ]
        );
    }

    public function canDelete()
    {
        return in_array($this->status, ['draft', 'rejected']) && $this->version == 0 && auth()->user()->checkPerms('personil.quota.delete');
    }

    public function createLogs($status, $description, $is_active = 1)
    {
        return $this->logs()->save(
            new QuotaLog(
                [
                    'status' => $status,
                    'keterangan' => $description,
                    'is_active' => $is_active
                ]
            )
        );
    }

    public function getUsedAttribute()
    {
        return $this->quotas->sum('total');
    }

    public function handleApproveSave($request)
    {
        \DB::beginTransaction();
        try {
            $approval_user = auth()->user();
            $is_approved = $request->is_submit === 'approved' ? true : false;
            $old_data = json_encode($this->load('approvals'));
            $this->touch();

            // Save Approval
            $approval = $this->approvals()->find($request->approval_id);
            $approval->status          = $is_approved ? 'approved' : 'rejected';
            $approval->keterangan      = $is_approved ? null : $request->keterangan;
            $approval->approved_at     = $is_approved ? now() : null;
            $approval->position_id     = $approval_user->position_id;
            $approval->user_id         = $approval_user->id;
            $approval->save();

            $notify = [
                'id' => $this->id,
                'type' => $request->module,
                'title' => in_array($this->status, ['completed']) ? 'Revisi ' : '' . 'Quota TAD | Tahun ' . $this->year . ' Semester ' . $this->semester,
                'from' => $approval_user->id,
                'link' => route('personil.quota.approval', $this->id),
                'message' => '',
                'content' => $this
            ];
            if ($is_approved) {
                $this->createLogs('approve', in_array($this->status, ['waiting.approval.upgrade', 'waiting.approval.revisi']) ? 'Menyetujui Revisi Periode Quota TAD' : 'Menyetujui Periode Quota TAD', 0);
                // Check Approval
                if ($this->approvals()->whereIn('status', ['draft', 'rejected'])->count() == 0) {
                    if ($this->status == 'waiting.approval.upgrade') {
                        $this->update([
                            'version'   => $this->version + 1,
                            'status'    => 'draft',
                        ]);
                        $this->quotas()->update(['status' => 'draft']);
                        // $this->createLogs('draft', 'Periode Quota TAD Telah Direvisi', 0);
                    } else {
                        $this->update([
                            'status' => 'completed',
                            'quota'  => $this->quota_lama,
                        ]);
                        $this->quotas()->update(['status' => 'approved']);
                        // $this->createLogs('completed', 'Periode Quota TAD Telah Completed', 0);
                    }
                }
                $this->saveLogNotifyApproval($request);
                // auth()->user()->storeLog($request->module, 'approval');
            } else {
                if (!in_array($this->status, ['waiting.approval', 'submit'])) {
                    $this->createLogs('reject', 'Menolak Revisi Periode Quota TAD: ' . $request->keterangan, 0);
                    $this->update(['status' => 'completed']);
                    auth()->user()->storeLog($request->module, 'reject');
                } else {
                    $this->createLogs('reject', 'Menolak Periode Quota TAD: ' . $request->keterangan, 0);
                    $this->update(['status' => 'rejected']);
                    auth()->user()->storeLog($request->module, 'reject');
                }
            }

            $new_data = json_encode($this->load('approvals'));
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
                'message' => 'Data successfully saved',
                'redirect' => route($request->route . '.index'),
            ]
        );
    }

    public function handleUpgrade($request)
    {
        \DB::beginTransaction();
        try {
            $this->update([
                'status' => 'waiting.approval.upgrade',
                'upgrade_reason' => $request->note
            ]);
            $this->generateApproval($request->module);
            $this->saveLogNotifyApproval($request);
            $this->createLogs('logs', 'Pengajuan Revisi Periode Quota');
            auth()->user()->storeLog($request->module, 'submit');
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
                'message' => 'Data successfully upgraded',
                'data'    => $this,
            ]
        );
    }

    public function saveLogNotifyApproval($request, $is_upgrade = false)
    {
        $content = json_decode($this);
        unset($content->quota);
        $notify = [
            'id' => $this->id,
            'type' => $request->module,
            // 'title' => in_array($this->status, ['waiting.approval.upgrade', 'waiting.approval.revisi', 'draft']) ? 'Revisi ' : '' . 'Quota TAD | Tahun ' . $this->year . ' Semester ' . $this->semester,
            'title' => 'Quota TAD | Tahun ' . $this->year . ' Semester ' . $this->semester,
            'from' => auth()->id(),
            'link' => route('personil.quota.approval', $this->id),
            'message' => 'Quota TAD membutuhkan persetujuan',
            'content' => $content
        ];

        $approval = $this->firstNewApprovalGet($request->module);
        $users = [];
        // notify approval
        if ($approval != null) {
            foreach ($approval->group->users as $qq) {
                // return $user;
                if (!in_array($qq->id, $users)) {
                    /* Send Notification */
                    $qq->notify(new Notify($notify));
                    $users[] = $qq->id;
                }
            }
        }
    }

    static function adjustQuotaByFulfillment()
    {
        $PERIODE = Self::select('id', 'year', 'semester', 'level')
            ->with(
                [
                    'quotas' => function ($q) {
                        $q->select(
                            'id',
                            'pengajuan_tad_quota_periode_id',
                            'org_struct_id',
                            'posisi_tad_id',
                            'quota'
                        );
                    }
                ]
            )
            ->get();
        foreach ($PERIODE as $periode) {
            // foreach ($periode->quotas as $quota) {
            //    $quota->fulfillment = $quota->dipakai;
            //    if ($quota->quota < $quota->dipakai) {
            //        $quota->quota = $quota->dipakai;
            //    } 
            //    $quota->save();
            // }
            $periode->quota         = $periode->quotas()->sum('quota');
            $periode->fulfillment   = $periode->quotas()->sum('fulfillment');
            $periode->status        = 'completed';
            $periode->save();
            $periode->createLogs('logs', 'Migrasi Quota');
        }
    }
}
