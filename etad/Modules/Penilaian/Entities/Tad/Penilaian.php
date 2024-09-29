<?php

namespace Modules\Penilaian\Entities\Tad;

use App\Entities\Activity;
use App\Entities\BaseModel;
use App\Entities\Traits\Approval;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Notifications\Notify;
use Modules\Penilaian\Entities\Tad\Jawaban;

class Penilaian extends BaseModel
{
    use Approval;

    protected $table = 'trans_penilaian_tad';
    protected $fillable = [
        'status',
        'unit_kerja_id',
        'unit_kerja_type',
        'tad_id',
        'kepegawaian_id',
        'masa_kerja',
        'periode_penilaian',
        'tahun',
        'semester',
        'masa_kontrak',
        'prestasi',
        'indisipliner',
        'saran',
        'status_perpanjangan',
        'to',
        'no_surat',
	'vendor_id',
	'personil'    
    ];

    protected $append = [
        'status_perpanjangan_label'
    ];

    protected $casts = [
        'periode_penilaian' => 'date',
    ];

    // protected $dates = ['periode_penilaian'];

    public function setPeriodePenilaianAttribute($value)
    {
        try {
            $this->attributes['periode_penilaian'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function getStatusPerpanjanganLabelAttribute()
    {
        switch ($this->status_perpanjangan) {
            case '1':
                return 'Tidak Diperpanjang';
            case '2':
                return 'Diperpanjang';
            default:
                return ' - ';
        }
    }

    public function jawaban()
    {
        return $this->hasMany(Jawaban::class, 'penilaian_id');
    }
    public function logs()
    {
        return $this->morphMany(Activity::class, 'target');
        return $this->hasMany(Logs::class, 'penilaian_tad_id');
    }
    public function penilai()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to');
    }
    public function so()
    {
        return $this
            ->belongsTo(OrgStruct::class, 'unit_kerja_id');
    }
    public function kepegawaian()
    {
        return $this->belongsTo(Kepegawaian::class, 'kepegawaian_id');
    }
    public function tad()
    {
        return $this->belongsTo(Tad::class, 'tad_id');
    }
    public function vendor()
    {
        return $this
            ->belongsTo(Vendor::class, 'vendor_id');
    }

    public function getPeriodePenilaianLabelAttribute()
    {
        return Self::BULAN_BY_ANGKA[$this->periode_penilaian] ?? '';
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
        if ($this->unit_kerja_type == Vendor::class) {
            if (isset($this->vendor->nama)) {
                return $this->vendor;
            }
        }
        return (object)[];
    }
    public function getUnitKerjaNameLabelAttribute()
    {
        if ($this->unit_kerja_type == OrgStruct::class) {
            return $this->so->name;
        }
        if ($this->unit_kerja_type == User::class) {
            return $this->creator->name;
        }
        if ($this->unit_kerja_type == Vendor::class) {
            return $this->vendor->nama;
        }
        return '--';
    }
    public function getUnitKerjaTypeLabelAttribute()
    {
        if ($this->unit_kerja_type == OrgStruct::class) {
            return $this->so->show_level;
        }
        if ($this->unit_kerja_type == User::class) {
            return 'User';
        }
        if ($this->unit_kerja_type == Vendor::class) {
            return 'Vendor';
        }
        return '--';
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            // $user = auth()->user();
	    // $personil = null;
	    $vendor_id          = null;
            // $unit_kerja         = null;
            // $unit_kerja_id      = null;
            // $unit_kerja_type    = null;
            // if ($user->isEhc) {
            //    if (isset($user->position->org_struct_id)) {
            //        $unit_kerja         = $user->org_name;
            //        $unit_kerja_id      = $user->position->org_struct_id;
            //        $unit_kerja_type    = \Modules\Master\Entities\SO\OrgStruct::class;
            //    } else {
            //        $unit_kerja         = $user->name;
            //        $unit_kerja_id      = $user->id;
            //        $unit_kerja_type    = \App\Entities\User::class;
            //    }
            // } elseif ($user->isVendor) {
            //    $unit_kerja_id          = $user->vendor_id;
            //    $unit_kerja_type        = \Modules\Master\Entities\Vendor::class;
            // }

            $tad_check = Tad::find($request->tad_id);
            if (
                empty($tad_check->nik) ||
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

            $is_exists = Self::query()
                // ->where('unit_kerja_id', $unit_kerja_id)
		// ->where('personil', $record->tad->nama)
		->where('vendor_id', $vendor_id)
                // ->where('unit_kerja_type', $unit_kerja_type)
                ->where('tad_id', $request->tad_id)
                ->where('kepegawaian_id', Tad::find($request->tad_id)->kepegawaian_id)
                ->where('periode_penilaian', Carbon::createFromFormat('d/m/Y', $request->periode_penilaian)->format('Y-m-d'))
                ->where('tahun', $request->tahun)
                ->where('semester', $request->semester)
                ->count() > 0;
            if ($is_exists) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Penilaian TAD di periode ' . (Self::BULAN_BY_ANGKA[$request->periode_penilaian] ?? '') . ' ' . $request->tahun . ' sudah ada.',
                        'trace' => '',
                    ],
                    422
                );
            }

            $record = new Self;
            $record->fill($request->all());
            // $record->unit_kerja_id      = $unit_kerja_id;
            // $record->unit_kerja_type    = $unit_kerja_type;
	    $record->personil = $record->tad->nama;
            $record->periode_penilaian  = Carbon::createFromFormat('d/m/Y', $request->periode_penilaian)->format('Y-m-d');
            $record->status             = $request->is_submit == 1 ? 'waiting.approval' : 'draft';
            $record->save();
            $record->kepegawaian_id = $record->tad->kepegawaian_id;
            $record->update();

            $pertanyaan = [];
            foreach ($request->question as $keyPenilai => $penilai) {
                foreach ($penilai as $key => $item) {
                    $pertanyaan[] = new Jawaban(
                        [
                            'pertanyaan_id' => $key,
                            'value' => $item,
                            'penilai' => $keyPenilai
                        ]
                    );
                }
            }
            $record->jawaban()->saveMany($pertanyaan);
            $data = 'Penilaian TAD ' . $record->tad->nama;

            if ($request->is_submit == 1) {
                $record->generateApproval($request->module);
                $record->saveLogNotifyApproval($request);

                $record->addLog('Submit Data ' . $data);
                auth()->user()->storeLog($request->module, 'create');
                auth()->user()->storeLog($request->module, 'submit');
            } else {
                /* Save Logs */
                $record->addLog(($record->created_at == $record->updated_at ? 'Menambahkan' : 'Mengubah') . ' Data ' . $data);
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
        $tad_check = Tad::find($request->tad_id);
        if (
            empty($tad_check->nik) ||
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

        $is_exists = Self::where('id', '!=', $this->id)
            // ->where('unit_kerja_id', $this->unit_kerja_id)
	    // ->where('personil', $this->tad->nama)
	    ->where('vendor_id', $request->vendor_id)
            ->where('unit_kerja_type', $this->unit_kerja_type)
            ->where('tad_id', $request->tad_id)
            ->where('kepegawaian_id', Tad::find($request->tad_id)->kepegawaian_id)
            ->where('periode_penilaian', Carbon::createFromFormat('d/m/Y', $request->periode_penilaian)->format('Y-m-d'))
            ->where('tahun', $request->tahun)
            ->where('semester', $request->semester)
            ->count() > 0;
        if ($is_exists) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Penilaian TAD di periode ' . (Self::BULAN_BY_ANGKA[$request->periode_penilaian] ?? '') . ' ' . $request->tahun . ' sudah ada.',
                    'trace' => '',
                ],
                422
            );
        }
        \DB::beginTransaction();
        try {
            $this->fill($request->all());
            $this->status   = $request->is_submit == 1 ? 'waiting.approval' : 'draft';
	    $this->personil = $this->tad->nama;
            $this->periode_penilaian  = Carbon::createFromFormat('d/m/Y', $request->periode_penilaian)->format('Y-m-d');
            $this->save();
            $this->kepegawaian_id = $this->tad->kepegawaian_id;
            $this->update();

            $pertanyaan = [];
            foreach ($request->question as $keyPenilai => $penilai) {
                foreach ($penilai as $key => $item) {
                    $pertanyaan[] = new Jawaban(
                        [
                            'pertanyaan_id' => $key,
                            'value' => $item,
                            'penilai' => $keyPenilai
                        ]
                    );
                }
            }
            $this->jawaban()->delete();
            $this->jawaban()->saveMany($pertanyaan);
            $data = 'Penilaian TAD ' . $this->tad->nama;

            if ($request->is_submit == 1) {
                $this->generateApproval($request->module);
                $this->saveLogNotifyApproval($request);

                $this->addLog('Submit Data ' . $data);
                auth()->user()->storeLog($request->module, 'update');
                auth()->user()->storeLog($request->module, 'submit');
            } else {
                /* Save Logs */
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
                'data'    => $this
            ]
        );
    }

    public function deleteByRequest($request)
    {
        \DB::beginTransaction();
        try {
            // dd(295, json_decode($this));
            $this->logs()->delete();
            $this->jawaban()->delete();
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
                'data'    => $this
            ]
        );
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
                'title' => 'Penilaian TAD ' . $this->tad->nama,
                'from' => auth()->id(),
                'link' => route('penilaian.tad.show', $this->id),
                'content' => $this
            ];

            $data = 'Penilaian TAD ' . $this->tad->nama;


            if ($is_approved) {
                // Check Approval
                if ($this->approvals()->whereIn('status', ['draft', 'rejected'])->count() == 0) {
                    if ($this->status === 'waiting.approval.upgrade') {
                        $this->update([
                            'version'   => $this->version + 1,
                            'status'    => 'draft',
                        ]);
                    } else {
                        $this->update(['status' => 'completed']);
                    }
                    // $notify['message'] = 'Proses Penilaian TAD telah selesai.';
                    // $this->creator->notify(new Notify($notify));
                }
                $this->saveLogNotifyApproval($request);
                $this->addLog('Menyetujui Data ' . $data);
                auth()->user()->storeLog($request->module, 'approval');
            } else {
                if (!in_array($this->status, ['waiting.approval', 'submit'])) {
                    $this->addLog('Menolak Revisi Data ' . $data . ' dengan alasan: ' . request()->get('note'));
                    $this->update(['status' => 'completed']);
                    auth()->user()->storeLog($request->module, 'reject');
                } else {
                    $this->addLog('Menolak Data ' . $data . ' dengan alasan: ' . request()->get('note'));
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

    public function saveLogNotifyApproval($request)
    {
        $notify = [
            'id' => $this->id,
            'type' => $request->module,
            'title' => in_array($this->status, ['waiting.approval.upgrade', 'waiting.approval.revisi', 'draft']) ? 'Revisi ' : '' . 'Penilaian TAD ' . $this->tad->nama,
            'from' => auth()->id(),
            'link' => route('penilaian.tad.show', $this->id),
            'message' => 'Penilaian TAD membutuhkan persetujuan',
            'content' => $this
        ];

        $checkFirstApprover = $this->approval($request->module)
            ->where('is_upgrade', false)
            ->whereStatus('draft')
            ->orderBy('order')
            ->whereIn('group_id', [6])
            ->first();

        if (!empty($checkFirstApprover)) {
            switch ($this->kepegawaian->unitKerja->level) {
                case 'division':
                case 'cabang':
                    $approval = $this->firstNewApprovalGet($request->module);
                    $userCabang = User::whereHas('position', function ($q) {
                        $q->where('org_struct_id',  $this->kepegawaian->unitKerja->id);
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
                    $userCabang = User::whereHas('position', function ($q) {
                        $q->where('org_struct_id',  $this->kepegawaian->unitKerja->parent_id);
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
        } else {
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
