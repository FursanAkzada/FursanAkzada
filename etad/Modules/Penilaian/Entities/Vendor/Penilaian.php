<?php

namespace Modules\Penilaian\Entities\Vendor;

use App\Entities\BaseModel;
use App\Entities\EHC\Cabang;
use App\Entities\Traits\Approval;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Master\Entities\Pertanyaan\Vendor as PertanyaanVendor;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Notifications\Notify;
use Modules\Penilaian\Entities\Vendor\Jawaban;
use Modules\Penilaian\Entities\Vendor\Review;
use Modules\Settings\Entities\Menu;

class Penilaian extends BaseModel
{
    use HasFactory, Approval;

    protected $table = 'trans_penilaian_vendor';
    protected $fillable = [
        'status',
        'unit_kerja_id',
        'unit_kerja_type',
        'vendor_id',
        'tahun',
        'semester',
        'tgl_penilaian',
        'jumlah_tad',
        'kesimpulan',
        'kritik',
        'saran',
        'to',
        'no_surat'
    ];
    protected $dates = ['tgl_penilaian'];

    public function setTglPenilaianAttribute($value)
    {
        $this->attributes['tgl_penilaian'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') ;
    }

    public function scopeMonitoring($query)
    {
        return $query->when(
            $keyword = request()->keyword,
            function ($q) use ($keyword) {
                $q->whereLike(['vendor.nama', 'cabang.CAPEM'], $keyword);
            }
        )
            ->when(
                $tahun = request()->tahun,
                function ($q) use ($tahun) {
                    $q->where('tahun', $tahun);
                }
            );
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'KDCAB');
    }

    public function logs()
    {
        return $this->hasMany(Logs::class, 'penilaian_vendor_id');
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'penilaian_vendor_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'penilaian_vendor_id');
    }
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to');
    }

    public function so()
    {
        return $this->belongsTo(OrgStruct::class, 'unit_kerja_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function reviewByRequest()
    {
        \DB::beginTransaction();
        try {
            $dataPost = new Review(
                [
                    'sign_by' => auth()->user()->id,
                    'sign_at' => date('Y-m-d H:i:s')
                ]
            );
            $this->review()->save($dataPost);
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
                'data'    => $this
            ]
        );
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $is_exists = Self::where('vendor_id', $request->vendor_id)
                // ->where('unit_kerja_id', $request->unit_kerja_id)
                ->where('unit_kerja_type', $request->unit_kerja_type)
                ->where('tahun', $request->tahun)
                ->where('semester', $request->semester)
                ->count() > 0;
            if ($is_exists) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Penilaian vendor di tahun ' . $request->tahun . ' semester '.$request->semester.' sudah ada.',
                        'trace' => '',
                    ],
                    422
                );
            }
            $so_id = $request->unit_kerja_id;
            $jumlahTad = Tad::where(
                [
                    'vendor_id' => $request->vendor_id
                ]
            )
                ->whereHas(
                    'kepegawaian',
                    function ($q) use ($so_id) {
                        if ($so_id) {
                            $q->where('cabang_id', $so_id);
                        }
                    }
                )
                ->count();
            $request->request->add(
                [
                    'cabang_id'     => $so_id,
                    'jumlah_tad'    => $jumlahTad
                ]
            );
            $record = new Self;
            $record->fill($request->all());
            $record->status = $request->is_submit == 1 ? 'submit' : 'draft';
            $record->save();

            $jawaban = [];
            foreach (($request->question ?? []) as $key => $item) {
                $jawaban[] = new Jawaban(
                    [
                        'pertanyaan_id' => $key,
                        'value' => $item
                    ]
                );
            }
            $record->jawaban()->saveMany($jawaban);
            if ($request->is_submit == 1) {
                $record->generateApproval($request->module);
                $record->saveLogNotifyApproval($request);
                auth()->user()->storeLog($request->module, 'create');
                auth()->user()->storeLog($request->module, 'submit');
                /* Save Logs */
                $record->createLogs('logs', 'Submit Penilaian', 0);
            } else {
                /* Save Logs */
                $record->createLogs('draft', 'Data Tersimpan Sebagai Draft');
                auth()->user()->storeLog($request->module, 'create');
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
                'data'    => $record
            ]
        );
    }

    public function updateByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $is_exists = Self::where('id', '!=', $this->id)
                ->where('vendor_id', $request->vendor_id)
                ->where('unit_kerja_type', $request->unit_kerja_type)
                ->where('tahun', $request->tahun)
                ->where('semester', $request->semester)
                ->count() > 0;
            if ($is_exists) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Penilaian vendor di tahun ' . $request->tahun . ' semester '.$request->semester.' sudah ada.',
                        'trace' => '',
                    ],
                    422
                );
            }
            $so_id = auth()->user()->position->struct_id ?? null;
            $jumlahTad = Tad::where(
                [
                    'vendor_id' => $request->vendor_id
                ]
            )
                ->whereHas(
                    'kepegawaian',
                    function ($q) use ($so_id) {
                        if ($so_id) {
                            $q->where('cabang_id', $so_id);
                        }
                    }
                )
                ->count();
            $request->request->add(
                [
                    'cabang_id'     => $so_id,
                    'jumlah_tad'    => $jumlahTad
                ]
            );
            $this->fill($request->all());
            $this->status = $request->is_submit == 1 ? 'submit' : 'draft';
            $this->save();

            $jawaban = [];
            foreach ($request->question as $key => $item) {
                $jawaban[] = new Jawaban(
                    [
                        'pertanyaan_id' => $key,
                        'value' => $item
                    ]
                );
            }
            $this->jawaban()->delete();
            $this->jawaban()->saveMany($jawaban);
            if ($request->is_submit == 1) {
                $this->generateApproval($request->module);
                /* Save Logs */
                $this->createLogs('logs', 'Submit Penilaian', 0);
                auth()->user()->storeLog($request->module, 'update');
                auth()->user()->storeLog($request->module, 'submit');
            } else {
                /* Save Logs */
                $this->createLogs('draft', 'Data Tersimpan Sebagai Draft');
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
                'message' => 'Data successfully saved',
                'data'    => $this
            ]
        );
    }

    public function deleteByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $this->logs()->delete();
            $this->delete();
            $this->jawaban()->delete();
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
                'data'    => $this
            ]
        );
    }

    public function getUnitKerjaAttribute()
    {
        if ($this->unit_kerja_type == OrgStruct::class) {
            if (isset($this->so->name)) {
                return $this->so;
            }
        }
        if ($this->unit_kerja_type == User::class) {
            if (isset($this->creator->name)) {
                return $this->creator;
            }
        }
        return (object)[];
    }
    public function getUnitKerjaTypeLabelAttribute()
    {
        if ($this->unit_kerja_type == OrgStruct::class) {
            return $this->so->show_level;
        }
        if ($this->unit_kerja_type == User::class) {
            return 'User';
        }
        return (object)[];
    }

    public function createLogs($status, $description, $is_active = 1)
    {
        return $this->logs()->save(
            new Logs(
                [
                    'status' => $status,
                    'keterangan' => $description,
                    'is_active' => $is_active
                ]
            )
        );
    }

    public function handleApproveSave($request)
    {
        \DB::beginTransaction();
        try {
            $this->module = null;
            $approval_user = auth()->user();
            $is_approved = $request->is_submit == 'approved' ? true : false;
            $old_data = json_encode($this->load('approvals'));
            $this->touch();

            // Save Approval
            $approval = $this->approvals()->find($request->approval_id);
            $approval->status          = $is_approved ? 'approved' : 'rejected';
            $approval->keterangan      = $is_approved ? null : $request->keterangan;
            $approval->approved_at     = $is_approved ? now() : null;
            $approval->position_id = auth()->user()->position_id;
            $approval->user_id         = auth()->id();
            $approval->save();

            $notify = [
                'id' => $this->id,
                'type' => $request->module,
                'title' => 'Penilaian Vendor ' . $this->vendor->nama . ' | Tahun ' . $this->tahun,
                'from' => auth()->id(),
                'link' => route('penilaian.vendor.show', $this->id),
                'content' => $this
            ];

            if ($is_approved) {
                $this->createLogs('approve', in_array($this->status, ['waiting.approval.upgrade', 'waiting.approval.revisi']) ? 'Menyetujui Revisi Penilaian Vendor' : 'Menyetujui Penilaian Vendor', 0);
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
                auth()->user()->storeLog($request->module, 'approval');
            } else {
                if(!in_array($this->status, ['waiting.approval', 'submit'])){
                    $this->createLogs('logs', 'Revisi Penilaian Ditolak, Keterangan: ' . $request->keterangan, 0);
                    $this->update(['status' => 'completed']);
                    auth()->user()->storeLog($request->module, 'reject');
                }else{
                    $this->createLogs('logs', 'Penilaian Ditolak, Keterangan: ' . $request->keterangan, 0);
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

    /** OTHER FUNCTIONS **/
    public function saveLogNotifyApproval($request)
    {
        $notify = [
            'id' => $this->id,
            'type' => $request->module,
            'title' => in_array($this->status, ['waiting.approval.upgrade', 'waiting.approval.revisi', 'draft']) ? 'Revisi ' : '' . 'Penilaian Vendor ' . $this->vendor->nama . ' Tahun ' . $this->tahun,
            'from' => auth()->id(),
            'link' => route('penilaian.vendor.show', $this->id),
            'message' => 'Penilaian Vendor membutuhkan persetujuan',
            'content' => $this
        ];

        $checkFirstApprover = $this->approval($request->module)
        ->where('is_upgrade', false)
        ->whereStatus('draft')
        ->orderBy('order')
        ->whereIn('group_id', [6])
        ->first();

        if(!empty($checkFirstApprover)){
            switch ($this->so->level) {
                case 'division':
                case 'cabang':
                    $approval = $this->firstNewApprovalGet($request->module);
                    $userCabang = User::whereHas('position', function($q){
                        $q->where('org_struct_id',  $this->unit_kerja_id);
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
                        $q->where('org_struct_id',  $this->so->parent_id);
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
    }
}
