<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use App\Entities\Traits\Approval;
use App\Entities\User;
use App\Models\Globals\Activity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Wawancara\Kompetensi;
use Modules\Pengajuan\Entities\Tad\DetailKandidat;
use Modules\Pengajuan\Entities\Tad\DetailWawancara;
use Modules\Pengajuan\Entities\Tad\Pewawancara;
use Modules\Pengajuan\Notifications\Notify;

class SummaryWawancara extends BaseModel
{
    use HasFactory, Approval;

    protected $table = 'trans_pengajuan_tad_summarywawancara';
    protected $fillable = [
        'kandidat_id',
        'status',
        'accepted',
        'accepted_at',
        'accepted_by',
        'keterangan',
        'tgl_wawancara',
        'alasan_pengunduran',
        'is_fallback',
	'personil'
    ];

    protected $dates = ['accepted_at', 'tgl_wawancara'];

    public function setAcceptedAtAttribute($value)
    {
        $this->attributes['accepted_at'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') :null;
    }
    public function setTglWawancaraAttribute($value)
    {
        $this->attributes['tgl_wawancara'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') :null;
    }
    public function kandidat()
    {
        return $this->belongsTo(DetailKandidat::class, 'kandidat_id');
    }

    public function details()
    {
        return $this->hasMany(DetailWawancara::class, 'summary_id');
    }

    public function pewawancaras()
    {
        return $this->hasMany(Pewawancara::class, 'wawancara_id');
    }

    public function scopeDiterima($query)
    {
        $query->where('accepted', 1);
    }
    public function scopeDitolak($query)
    {
        $query->where('accepted', 2);
    }

    public function generateDetails()
    {
        // generate details
        $kompetensi = Kompetensi::whereHas('pertanyaan')
            ->orderBy('kompetensi', 'desc')
            ->get();
        foreach ($kompetensi as $d) {
            foreach ($d->pertanyaan as $dd) {
                DetailWawancara::firstOrCreate([
                    'summary_id' => $this->id,
                    'pertanyaan_id' => $dd->id
                ]);
            }
        }
    }

    public function updateByRequest($request)
    {
        \DB::beginTransaction();
        try {
            // cek kondisi
            foreach ($request->details as $val) {
                if (!isset($val['answer'])) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Poin wawancara belum terisi sepenuhnya!',
                            'trace' => null,
                        ],
                        422
                    );
                }
                $detail = $this->details()->firstOrNew([
                    'pertanyaan_id' => $val['pertanyaan_id']
                ]);
                $detail->value = $val['answer'] ?? 0;
                $this->details()->save($detail);
            }

            foreach ($request->details as $val) {
                $detail = $this->details()->firstOrNew([
                    'pertanyaan_id' => $val['pertanyaan_id']
                ]);
                $detail->value = $val['answer'] ?? 0;
                $this->details()->save($detail);
            }
            $this->tgl_wawancara = $request->tgl_wawancara;
            $this->keterangan = $request->keterangan;
	    $this->personil = $this->kandidat->tad->nama;
            $this->save();
            $this->savePewawancara($request);

            $data = 'Wawancara | Nama Kandidat ' . $this->kandidat->tad->nama;

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

    public function savePewawancara($request)
    {
        $cek_list = [];
        foreach ($request->pewawancaras as $val) {
            $cek_list[] = (int)$val;
            $detail = $this->pewawancaras()->firstOrNew([
                'user_id' => (int)$val
            ]);
            $this->pewawancaras()->save($detail);
        }
        $this->pewawancaras()->whereNotIn('user_id', $cek_list)->delete();
    }

    public function handlePengajuanMundur($request)
    {
        \DB::beginTransaction();
        try {
            $this->is_fallback = True;
            $this->alasan_pengunduran = $request->alasan_pengunduran;
            $this->status = 'completed';
            $this->update();
            if ($this->details()->count() != 0) {
                $this->details()->delete();
            }
            $this->kandidat->summary->pengajuan->pivotTad()->detach();
            $this->kandidat->update(['status' => 'memundurkan diri']);
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
                'message' => 'Data successfully saved',
                'redirect' => route($request->route . '.index'),
            ]
        );
    }

    public function handleApproveSave($request)
    {
        \DB::beginTransaction();
        try {
            $data = 'Wawancara | Nama Kandidat ' . $this->kandidat->tad->nama;

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
                'title' => in_array($this->status, ['completed']) ? 'Revisi ' : '' . 'Wawancara | Nama Kandidat ' . $this->kandidat->tad->nama,
                'from' => $approval_user->id,
                'link' => route('pengajuan.wawancara.approval', $this->id),
                'message' => '',
                'content' => $this
            ];
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

    /** OTHER FUNCTIONS **/
    public function saveLogNotifyApproval($request)
    {
        $notify = [
            'id' => $this->id,
            'type' => $request->module,
            'title' => in_array($this->status, ['waiting.approval.upgrade', 'waiting.approval.revisi', 'draft']) ? 'Revisi ' : '' . 'Wawancara | Nama Kandidat ' . $this->kandidat->tad->nama,
            'from' => auth()->id(),
            'link' => route('pengajuan.wawancara.approval', $this->id),
            'message' => 'Wawancara membutuhkan persetujuan',
            'content' => $this
        ];

        $checkFirstApprover = $this->approval($request->module)
                ->where('is_upgrade', false)
                ->whereStatus('draft')
                ->orderBy('order')
                ->whereIn('group_id', [6])
                ->first();
        if(!empty($checkFirstApprover)){
            if(!empty($this->kandidat->summary->pengajuan->so->level)){
                switch ($this->kandidat->summary->pengajuan->so->level) {
                    case 'division':
                    case 'cabang':
                        $approval = $this->firstNewApprovalGet($request->module);
                        $userCabang = User::whereHas('position', function($q){
                            $q->where('org_struct_id',  $this->kandidat->summary->pengajuan->so_id);
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
                            $q->where('org_struct_id',  $this->kandidat->summary->pengajuan->so->parent_id);
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
                break;
            case 'history':
                return $user->checkPerms($perms . '.view');
                break;

            case 'edit':
                $checkStatus = in_array($this->status, ['new', 'draft', 'rejected']);
                return $checkStatus && $user->checkPerms($perms . '.edit');
                break;
            case 'tracking':
                $checkStatus = in_array($this->status, ['waiting.approval', 'rejected', 'completed']);
                return $checkStatus && $user->checkPerms($perms . '.view');
            default:
                return false;
                break;
        }
    }
}
