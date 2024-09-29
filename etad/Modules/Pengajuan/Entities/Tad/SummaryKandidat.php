<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use App\Entities\Traits\Approval;
use App\Entities\User;
use App\Models\Globals\Activity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Tad\DetailKandidat;
use Modules\Pengajuan\Entities\Tad\Pengajuan;
use Modules\Pengajuan\Entities\Tad\Requirement;
use Modules\Pengajuan\Notifications\Notify;

class SummaryKandidat extends BaseModel
{
    use HasFactory, Approval;

    protected $table = 'trans_pengajuan_tad_summarykandidat';
    protected $fillable = [
        'requirement_id',
        'pengajuan_id',
        'status',
        'accepted',
        'accepted_at',
        'accepted_by',
        'batch',
    ];
    protected $dates = ['accepted_at'];
    public function setAcceptedAtAttribute($value)
    {
        $this->attributes['accepted_at'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') :null;
    }

    public function requirement()
    {
        return $this->belongsTo(Requirement::class, 'requirement_id');
    }

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'pengajuan_id');
    }

    public function details()
    {
        return $this->hasMany(DetailKandidat::class, 'summary_id');
    }

    public function logs()
    {
        return $this->morphMany(Activity::class, 'target');
    }

    public function scopeDiterima($query)
    {
        $query->where('accepted', 1);
    }
    public function scopeDitolak($query)
    {
        $query->where('accepted', 2);
    }

    public function storeKandidatByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $user = auth()->user();
            if ($request->kandidat_id) {
                // simpan details
                if ($request->kandidat_id != null) {
                    foreach ($request->kandidat_id as $key => $val) {
                        $tad_check = Tad::find($val);
                        if (empty($tad_check->nik) ||
                            empty($tad_check->email) ||
                            empty($tad_check->city_id) ||
                            empty($tad_check->tempat_lahir) ||
                            empty($tad_check->files->where('flag', 'foto3x4')) ||
                            empty($tad_check->files->where('flag', 'foto_fullbody')) ||
                            empty($tad_check->files->where('flag', 'cv')) ||
                            empty($tad_check->jenis_kelamin) ||
                            empty($tad_check->agama_id) ||
                            empty($tad_check->tanggal_lahir) ||
                            empty($tad_check->pendidikan_id) ||
                            empty($tad_check->jabatan_id)
                            ) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Data kandidat belum lengkap!',
                                'trace' => null,
                            ], 422);
                        }
                    }
                    foreach ($request->kandidat_id as $key => $val) {
                        DetailKandidat::firstOrCreate([
                            'summary_id' => $request->summary_id,
                            'tad_id' => $val,
                        ]);
                    }
                }
            } else {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Calon kandidat tidak boleh kosong!',
                        'trace' => null,
                    ],
                    422
                );
            }

            // $this->details()->sync($request->kandidat_id);
            $this->pengajuan->pivotTad()->sync($request->kandidat_id);
            $this->status = 'draft';
            $this->save();
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
                'message' => 'Data successfuly store',
                'data'    => $this
            ]
        );
    }

    public function updateByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $data = 'Kandidat : ' . $this->pengajuan->no_tiket;
            if ($this->details->count() == 0) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Calon kandidat tidak boleh kosong!',
                        'trace' => null,
                    ],
                    422
                );
            }

            if ($request->is_submit == 1) {
                $this->status = $request->is_submit == 1 ? 'waiting.approval' : 'draft';
                $this->save();
                $this->generateApproval($request->module);
                $this->saveLogNotifyApproval($request);
                $this->addLog('Submit Data ' . $data);
                auth()->user()->storeLog($request->module, 'submit');
            } else {
                $this->status = 'draft';
                $this->save();
                $this->addLog('Mengubah Data ' . $data);
                auth()->user()->storeLog($request->module, 'update');
            }
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

    public function handleApproveSave($request)
    {
        \DB::beginTransaction();
        try {
            $this->module = null;
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
                'title' => in_array($this->status, ['completed']) ? 'Revisi ' : '' . 'Kandidat : ' . $this->pengajuan->no_tiket,
                'from' => $approval_user->id,
                'link' => route('pengajuan.kandidat.approval', $this->id),
                'message' => '',
                'content' => $this
            ];
            $data = 'Kandidat : ' . $this->pengajuan->no_tiket;

            if ($is_approved) {
                // Check Approval
                if ($this->approvals()->whereIn('status', ['draft', 'rejected'])->count() == 0) {
                    if ($this->status == 'waiting.approval.upgrade') {
                        $this->update([
                            'version'   => $this->version + 1,
                            'status'    => 'draft',
                        ]);
                    } else {
                        $this->update(['status' => 'completed']);
                        $this->details()->update(['status' => 'proses']);
                    }
                }
                $this->saveLogNotifyApproval($request);
                $this->addLog('Menyetujui Data ' . $data);
                auth()->user()->storeLog($request->module, 'approval');
            } else {
                if (!in_array($this->status, ['waiting.approval', 'submit'])) {
                    $this->update(['status' => 'completed']);
                    $this->addLog('Menolak Revisi Data ' . $data . ' dengan alasan: ' . request()->get('note'));
                    auth()->user()->storeLog($request->module, 'reject');
                } else {
                    $this->update(['status' => 'rejected']);
                    $this->addLog('Menolak Data ' . $data . ' dengan alasan: ' . request()->get('note'));
                    auth()->user()->storeLog($request->module, 'reject');
                }
                $notify = [
                    'id' => $this->id,
                    'type' => $request->module,
                    'from' => auth()->id(),
                    'link' => route('pengajuan.kandidat.approval', $this->id),
                    'title' => 'Persetujuan Ditolak',
                    'message' => (in_array($this->status, ['waiting.approval.upgrade', 'waiting.approval.revisi']) ? 'Revisi ' : '') . 'Kandidat : ' . $this->pengajuan->no_tiket,
                    'content' => $this
                ];
                $this->creator->notify(new Notify($notify));
            }

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

    /** OTHER FUNCTIONS **/
    public function saveLogNotifyApproval($request)
    {
        $notify = [
            'id' => $this->id,
            'type' => $request->module,
            'title' => in_array($this->status, ['waiting.approval.upgrade', 'waiting.approval.revisi', 'draft']) ? 'Revisi ' : '' . 'Kandidat : ' . $this->pengajuan->no_tiket,
            'from' => auth()->id(),
            'link' => route('pengajuan.kandidat.approval', $this->id),
            'message' => 'Kandidat membutuhkan persetujuan',
            'content' => $this
        ];

        $checkFirstApprover = $this->approval($request->module)
                ->where('is_upgrade', false)
                ->whereStatus('draft')
                ->orderBy('order')
                ->whereIn('group_id', [6])
                ->first();
        if(!empty($checkFirstApprover)){
            if(!empty($this->pengajuan->so->level)){
                switch ($this->pengajuan->so->level) {
                    case 'division':
                    case 'cabang':
                        $approval = $this->firstNewApprovalGet($request->module);
                        $userCabang = User::whereHas('position', function($q){
                            $q->where('org_struct_id',  $this->pengajuan->so_id);
                        })->pluck('id')->toArray();
                        $users = $userCabang;
                        // notify approval
                        if ($approval != null) {
                            foreach ($approval->group->users as $qq) {
                                // return $user;
                                if (in_array($qq->id, $users)) {
                                    /* Send Notification */
                                    $qq->notify(new Notify($notify));
                                    $users[] = $qq->id;
                                }
                            }
                        }
                        break;
                    case 'capem':
                        $approval = $this->firstNewApprovalGet($request->module);
                        $userCabang = User::whereHas('position', function($q){
                            $q->where('org_struct_id',  $this->pengajuan->so->parent_id);
                        })->pluck('id')->toArray();
                        $users = $userCabang;
                        // notify approval
                        if ($approval != null) {
                            foreach ($approval->group->users as $qq) {
                                // return $user;
                                if (in_array($qq->id, $users)) {
                                    /* Send Notification */
                                    $qq->notify(new Notify($notify));
                                    $users[] = $qq->id;
                                }
                            }
                        }
                        break;
                    default:
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
                        break;
                }
            }else{
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
        }else{
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
    }

    public function checkAction($action, $perms, $summary = null)
    {
        $user = auth()->user();

        switch ($action) {
            case 'create':
                return $user->checkPerms($perms . '.edit');

            case 'show':
                return $user->checkPerms($perms . '.view');
            case 'history':
                return $user->checkPerms($perms . '.view');

            case 'edit':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.edit');
            case 'tracking':
                $checkStatus = in_array($this->status, ['waiting.approval', 'rejected', 'completed']);
                return $checkStatus && $user->checkPerms($perms . '.view');
            default:
                return false;
        }
    }
}
