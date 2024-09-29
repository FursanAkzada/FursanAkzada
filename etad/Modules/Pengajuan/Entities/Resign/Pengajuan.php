<?php

namespace Modules\Pengajuan\Entities\Resign;

use App\Entities\BaseModel;
use App\Entities\EHC\Cabang;
use App\Entities\Traits\Approval;
use App\Entities\Traits\HasFiles;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\SO\Positions;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Entities\Resign\Logs;
use Modules\Pengajuan\Entities\Tad\Quota;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;
use Modules\Pengajuan\Notifications\Notify;
use Modules\Settings\Entities\Menu;


class Pengajuan extends BaseModel
{
    use HasFactory, Approval, HasFiles;

    protected $table    = 'trans_pengajuan_resign';
    protected $fillable = [
        'status',
        'no',
        'perihal',
        'unit_kerja_id',
        'unit_kerja_type',
        'no_tiket',
        'cabang_id',
        'surat_filename',
        'surat_filepath',
        'tgl_pengajuan',
        'perihal',
        'pembukaan',
        'isi_surat',
	'personil'
    ];

    protected $dates = ['tgl_pengajuan'];

    public function setTglPengajuanAttribute($value)
    {
        $this->attributes['tgl_pengajuan'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }

    public function scopeMonitoring($query)
    {
        $query->when(
            $keyword = request()->keyword,
            function ($q) use ($keyword) {
                $q->whereLike(['no_tiket', 'created_at'], $keyword);
            }
        )
            ->when(
                $status = request()->status,
                function ($q) use ($status) {
                    $q->where('active_logs.status', $status);
                }
            );
    }

    public function cabang()
    {
        return $this->belongsTo(OrgStruct::class, 'cabang_id', 'KDCAB');
    }

    public function cc()
    {
        return $this->belongsToMany(Positions::class, CC::class, 'pengajuan_id', 'user_id');
    }
    public function to()
    {
        return $this->belongsToMany(Positions::class, 'trans_pengajuan_resign_to', 'trans_id', 'user_id');
    }
    public function ccHasMany()
    {
        return $this->hasMany(CC::class, 'pengajuan_id');
    }

    public function pegawai()
    {
        return $this->belongsToMany(Tad::class, 'trans_pengajuan_resign_pegawai', 'pengajuan_id', 'tad_id')
            ->withPivot('id', 'kepegawaian_id', 'alasan', 'tanggal_resign', 'tanggal_efektif');
    }
    public function employees()
    {
        return $this->hasMany(Pegawai::class, 'pengajuan_id');
    }

    public function so()
    {
        return $this->belongsTo(OrgStruct::class, 'unit_kerja_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'unit_kerja_id');
    }

    public function unitKerja()
    {
        return $this->morphTo('unit_kerja');
    }

    public function logs()
    {
        return $this->hasMany(Logs::class, 'pengajuan_id');
    }

    public function active_logs()
    {
        return $this->hasOne(Logs::class, 'pengajuan_id')->where('is_active', 1);
    }

    public function disableStatusBefore()
    {
        return $this->active_logs()
            ->update(
                [
                    'is_active' => 0
                ]
            );
    }

    public function generateTicketNumber()
    {
        $last = $this->orderBy('id', 'desc')->first();
        $lastNumber = $last ? substr($last->no_tiket, -3) : 000;
        return 'RSGN-' . Carbon::now()->format('dmy') . str_pad($lastNumber + 1, 3, 0, STR_PAD_LEFT);
    }

    public function scopeOtorisasi()
    {
        return $this->where(
            function ($q) {
                $q->whereHas(
                    'logs',
                    function ($w) {
                        $w->where('status', 'waiting.hc');
                        $w->where('is_active', 1);
                        $w->orWhere('status', 'verification.hc');
                    }
                );
            }
        );
    }

    public function scopeOngoing($query)
    {
        return $query->whereHas(
            'active_logs',
            function ($q) {
                $q->where('status', '!=', 'closed');
                $q->where('status', '!=', 'approved.hc');
            }
        );
    }

    public function show_tgl_pengajuan()
    {
        return Carbon::createFromFormat('d/m/Y', $this->tgl_pengajuan);
    }

    public function saveCc($data)
    {
        $exists = [];
        if (!empty($data)) {
            foreach ($data as $user_id) {
                if ($user_id) {
                    $cc = $this->ccHasMany()->firstOrCreate(['user_id' => $user_id]);
                    $exists[] = $cc->id;
                }
            }
        }
        $this->ccHasMany()->whereNotIn('id', $exists)->delete();
    }

    public static function createByRequest($request)
    {
        DB::beginTransaction();
        try {
            $pegawai = [];
            foreach ($request->pegawai as $key => $item) {
                $tad_check = Tad::find($item['id']);
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
            }

            $user = auth()->user();
            $record = new Self;
            $record->no_tiket = (new Self)->generateTicketNumber();
            $record->unit_kerja_id = $request->unit_kerja_id;
            $record->unit_kerja_type = $request->unit_kerja_type;
            $record->perihal = $request->perihal;
            $record->no = $request->no;
            $record->pembukaan = $request->pembukaan;
            $record->isi_surat = $request->isi_surat;
            $record->tgl_pengajuan = $request->tgl_pengajuan;
	    foreach ($request->pegawai as $key => $pers) {
                $pers_tad = Tad::find($pers['id']);
		$record->personil = $pers_tad->nama;
	    }
	    // $record->personil = $record->tad->nama;
            // $record->status = $request->is_submit ? 'submit' : 'draft';
            $record->status = $request->is_submit == 1 ? 'waiting.approval' : 'draft';
            $record->save();

            /* Save Pegawai */

            foreach ($request->pegawai as $key => $item) {
                $personil_tad = Tad::find($item['id']);
		// $record->personil = $personil_tad->nama;
                $pegawai[$item['id']] = [
                    'kepegawaian_id'    => $personil_tad->kepegawaian_id,
                    'alasan'            => $item['alasan'],
                    'tanggal_resign'    => Carbon::createFromFormat('d/m/Y', $item['tanggal_resign'])->format('Y-m-d'),
                    'tanggal_efektif'   => Carbon::createFromFormat('d/m/Y', $item['tanggal_efektif'])->format('Y-m-d'),
                    'created_by'        => auth()->id(),
                    'created_at'        => now(),
                ];
            }
            $record->pegawai()->sync($pegawai);

            $record->saveFilesByTemp($request->uploads, $request->module, 'surat');
            $record->saveCc($request->user_id ?? []);
            $record->to()->sync($request->to ?? []);
            if ($request->is_submit == 1) {
                $record->generateApproval($request->module);
                foreach ($record->pegawai as $key => $pegawai) {
                    $personil_tad = Tad::with('kepegawaian')->find($pegawai->kepegawaian->tad_id);
                    $personil_tad->lock_id      = $pegawai->pivot->id;
                    $personil_tad->lock_type    = Modules\Pengajuan\Entities\Resign\Pegawai::class;
                    $personil_tad->save();
                }
                $record->saveLogNotifyApproval($request);

                /* Save Logs */
                $record->createLogs('logs', 'Submit pengajuan', 0);
                auth()->user()->storeLog($request->module, 'create');
                auth()->user()->storeLog($request->module, 'submit');

                $notify = [
                    'id'        => $record->id,
                    'type'      => $request->module,
                    'title'     => 'Pengajuan Resign : ' . $record->no_tiket,
                    'from'      => auth()->id(),
                    'link'      => route('pengajuan.resign.approval', $record->id),
                    'message'   => 'Pengajuan Resign membutuhkan persetujuan',
                    'content'   => $record
                ];
                // CC
                $notify['message'] = auth()->user()->name . ' membuat Pengajuan Resign.';
                foreach (($request->user_id ?? []) as $cc) {
                    $user = User::find($cc);
                    $user->notify(new Notify($notify));
                }
            } else {
                /* Save Logs */
                $record->createLogs('draft', 'Data Tersimpan Sebagai Draft');
                auth()->user()->storeLog($request->module, 'create');
            }

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
        DB::beginTransaction();
        try {
            $pegawai = [];
            foreach ($request->pegawai as $key => $item) {
                $tad_check = Tad::find($item['id']);
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
            }
            foreach ($request->pegawai as $key => $item) {
                $personil_tad   = Tad::find($item['id']);
		$this->personil = $personil_tad->nama;

                $pegawai[$item['id']] = [
                    'kepegawaian_id'    => $personil_tad->kepegawaian_id,
                    'alasan'            => $item['alasan'],
                    'tanggal_resign'    => Carbon::createFromFormat('d/m/Y', $item['tanggal_resign'])->format('Y-m-d'),
                    'tanggal_efektif'   => Carbon::createFromFormat('d/m/Y', $item['tanggal_efektif'])->format('Y-m-d'),
                    'updated_by'        => auth()->id(),
                    'updated_at'        => now(),
                ];
            }
            $this->pegawai()->sync($pegawai);

            $data = $request->all();
            $data['tgl_pengajuan'] = $request->tgl_pengajuan;
            // $data['status'] = $request->is_submit ? 'submit' : 'draft';
            $data['status'] = $request->is_submit == 1 ? 'waiting.approval' : 'draft';
            $old = $this->replicate();
	    // $this->personil = $this->tad->nama;
            $this->update($data);
            $this->saveCc($request->user_id ?? []);
	    $this->to()->sync($request->to ?? []);

            /* File Surat */
            $this->saveFilesByTemp($request->uploads, $request->module, 'surat');
            // $this->saveFilesByTemp($request->uploads, $request->module, 'surat');

            /* Save Pegawai */
            if ($request->is_submit == 1) {
                $this->generateApproval($request->module);
                foreach ($this->pegawai as $key => $pegawai) {
                    $personil_tad = Tad::with('kepegawaian')->find($pegawai->kepegawaian->tad_id);
                    $personil_tad->lock_id      = $pegawai->pivot->id;
                    $personil_tad->lock_type    = Modules\Pengajuan\Entities\Resign\Pegawai::class;
                    $personil_tad->save();
                }
                $this->saveLogNotifyApproval($request);
                /* Save Logs */
                $this->createLogs('logs', 'Submit pengajuan', 0);
                auth()->user()->storeLog($request->module, 'update');
                auth()->user()->storeLog($request->module, 'submit');

                $notify = [
                    'id' => $this->id,
                    'type' => $request->module,
                    'title' => 'Pengajuan Resign : ' . $this->no_tiket,
                    'from' => auth()->id(),
                    'link' => route('pengajuan.resign.approval', $this->id),
                    'message' => 'Pengajuan Resign membutuhkan persetujuan.',
                    'content' => $this
                ];
                // CC
                $notify['message'] = auth()->user()->name . ' membuat Pengajuan Resign.';
                foreach (($request->user_id ?? []) as $cc) {
                    $user = User::find($cc);
                    $user->notify(new Notify($notify));
                }
            } else {
                /* Save Logs */
                $this->createLogs('draft', 'Data Tersimpan Sebagai Draft');
                auth()->user()->storeLog($request->module, 'update');
            }

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
            Storage::delete([$this->surat_filepath]);
            $this->pegawai()->detach();
            $this->delete();
            auth()->user()->storeLog($request->module, 'delete');
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
        DB::beginTransaction();
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
                'title' => 'Pengajuan Resign : ' . $this->no_tiket,
                'from' => $approval_user->id,
                'link' => route('pengajuan.resign.approval', $this->id),
                'message' => '',
                'content' => $this
            ];

            if ($is_approved) {
                $this->createLogs('logs', 'Menyetujui Pengajuan Resign', 0);
                // Check Approval
                if ($this->approvals()->whereIn('status', ['draft', 'rejected'])->count() == 0) {
                    $this->update(['status' => 'completed']);
                    $this->load('pegawai.kepegawaian');
                    foreach ($this->pegawai as $key => $pegawai) {
                        $personil_tad = Tad::with('kepegawaian')->find($pegawai->kepegawaian->tad_id);

                        $kepegawaian    = Kepegawaian::find($pegawai->kepegawaian_id);
                        if ($kepegawaian) {
                            $new_kepegawaian = new Kepegawaian;
                            $arr = $kepegawaian->toArray();
                            if (isset($arr['in_at']) && $arr['in_at']) {
                                $arr['in_at'] = Carbon::parse($arr['in_at'])->format('d/m/Y');
                            }
                            if (isset($arr['mutation_at']) && $arr['mutation_at']) {
                                $arr['mutation_at'] = Carbon::parse($arr['mutation_at'])->format('d/m/Y');
                            }
                            if (isset($arr['resign_at']) && $arr['resign_at']) {
                                $arr['resign_at'] = Carbon::parse($arr['resign_at'])->format('d/m/Y');
                            }
                            if (isset($arr['out_at']) && $arr['out_at']) {
                                $arr['out_at'] = Carbon::parse($arr['out_at'])->format('d/m/Y');
                            }
                            if (isset($arr['contract_due']) && $arr['contract_due']) {
                                $arr['contract_due'] = Carbon::parse($arr['contract_due'])->format('d/m/Y');
                            }
                            $new_kepegawaian->fill($arr);
                            $new_kepegawaian->previous_employment_id        = $kepegawaian->id;
                            $new_kepegawaian->pengajuan_resign_pegawai_id   = $pegawai->pivot->id;
                            $new_kepegawaian->resign_at                     = Carbon::parse($pegawai->pivot->tanggal_resign)->format('d/m/Y');
                            $new_kepegawaian->out_at                        = Carbon::parse($pegawai->pivot->tanggal_efektif)->format('d/m/Y');
                            $new_kepegawaian->status                        = Kepegawaian::RESIGN;
		            $new_kepegawaian->save();

                            $personil_tad->kepegawaian_id   = $new_kepegawaian->id;
                            $personil_tad->save();

                            $pegawai->pivot->kepegawaian_id = $new_kepegawaian->id;
                            $pegawai->pivot->save();

                            // dd(
                            //     530,
                            //     json_decode($new_kepegawaian),
                            //     json_decode($personil_tad),
                            //     json_decode($pegawai),
                            //     json_decode($pegawai->pivot),
                            // );
                        }

                        $quota_periode = QuotaPeriode::where('level', $kepegawaian->unitKerja->level)
                            ->where('year', $kepegawaian->year)
                            ->where('semester', $kepegawaian->semester)
                            ->first();
                        $quota_periode->fulfillment = $quota_periode->fulfillment - 1;
                        $quota_periode->save();

                        $quota = Quota::where('pengajuan_tad_quota_periode_id', $quota_periode->id)
                            ->where('org_struct_id', $kepegawaian->cabang_id)
                            ->where('posisi_tad_id', $kepegawaian->jabatan_id)
                            ->first();
                        $quota->fulfillment -= 1;
                        $quota->save();
                    }
                    Artisan::call('push:personil-resign');
                }
                $this->saveLogNotifyApproval($request);
                auth()->user()->storeLog($request->module, 'approval');
            } else {
                $data = $this->no_tiket;
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
                'redirect' => route($request->route . '.index'),
            ]
        );
    }

    public function saveLogNotifyApproval($request)
    {
        $notify = [
            'id' => $this->id,
            'type' => $request->module,
            'title' => 'Pengajuan Resign : ' . $this->no_tiket,
            'from' => auth()->id(),
            'link' => route('pengajuan.resign.approval', $this->id),
            'message' => 'Pengajuan Resign membutuhkan persetujuan',
            'content' => $this
        ];

        $checkFirstApprover = $this->approval($request->module)
            ->where('is_upgrade', false)
            ->whereStatus('draft')
            ->orderBy('order')
            ->whereIn('group_id', [6])
            ->first();
        if (!empty($checkFirstApprover)) {
            switch ($this->so->level) {
                case 'division':
                case 'cabang':
                    $approval = $this->firstNewApprovalGet($request->module);
                    $userCabang = User::whereHas('position', function ($q) {
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
                    $userCabang = User::whereHas('position', function ($q) {
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


    public function getUnitKerjaName()
    {
        if ($this->unit_kerja_type == OrgStruct::class) {
            if (isset($this->so->name)) {
                return $this->so->name;
            }
        }
        if ($this->unit_kerja_type == Vendor::class) {
            if (isset($this->vendor->nama)) {
                return $this->vendor->nama . ' (Vendor)';
            }
        }
        return '';
    }

    function _syncToEhc($tad, $kepegawaian)
    {
        if (env('DB_SYNC', false)) {
            $inquiry_payload = [
                'NIO'       => $kepegawaian->nio,
		'ReqDate'   => now()->format('Y-m-d'),
            ];
            $inquiry_payload['Signature'] = base64_encode(hash_hmac(
                'sha256',
                $inquiry_payload['NIO'] . '|' . $inquiry_payload['ReqDate'],
		'jatim',
                true
            ));
            $inquery_tad_response = Http::withHeaders(
                [
                    'Authorization' => 'Basic ' . base64_encode('prgm:pragmainformatika')
                ]
            )->withoutVerifying()
                ->post(
                    'https://sriwijaya.bankjatim.co.id/EHC_SURROUNDING/rest/ETAD/InquiryTAD',
                    $inquiry_payload
                );
            $inquery_tad_response_json = $inquery_tad_response->json();
            // dd(275, $inquery_tad_response_json, $inquery_tad_response_json['ListTAD']);
            try {
                $upsert_payload = [
                    "NAMA"              => $tad->nama,
                    "NIO"               => $kepegawaian->nio,
                    "CABANG"            => $kepegawaian->unitKerja->code,
                    "UNIT_KERJA"        => $kepegawaian->jabatan->NM_UNIT,
                    "JENIS_JABATAN"     => $kepegawaian->jabatan->jenis,
                    "ALAMAT"            => $tad->alamat_lengkap,
                    "KOTA"              => $tad->city->name,
                    "TGL_MASUK"         => $kepegawaian->in_at->format('Y-m-d h:i:s'),
                    "BLN_BERAKHIR"      => $kepegawaian->contract_due
                        ? $kepegawaian->contract_due->format('m')
                        : (
                            $tad->date_old_contract ? $tad->date_old_contract->format('m') : now()->format('m')
                        ),
                    "TGL_LAHIR"         => $tad->tanggal_lahir->format('Y-m-d h:i:s'),
                    "TEMPAT_LAHIR"      => $tad->tempat_lahir,
                    "STAT_AKTIF"        => "0",
                    "JNS_KELAMIN"       => $tad->jenis_kelamin,
                    "STAT_KAWIN"        => $tad->status_perkawinan === "Menikah" ? '1' : ($tad->status_perkawinan === "Lajang" ? 0 : 2),
                    "NAMA_PERUSAHAAN"   => $kepegawaian->vendor->nama,
                    // "NO_SK"             => $this->wawancara->kandidat->summary->requirement->pengajuan->no_tiket,
                    "NO_SK"             => $kepegawaian->no_sk,
                    "PENDIDIKAN"        => $tad->pendidikan_id,
                    "REKENING"          => $tad->rekening_bjtm,
                    "STKANTOR"          => $kepegawaian->unitKerja->level === 'cabang' ? "CB" : 'KP',
                    "TGL_PERSETUJUAN"   => now()->format('Y-m-d h:i:s'),
                    "PENEMPATAN"        => $kepegawaian->unitKerja->name,
                    "KETERANGAN"        => "Resign",
                    "AGAMA"             => $tad->agama_id,
                    "NOHP"              => $tad->telepon,
                ];
                $upsert_payload['Signature'] = base64_encode(hash_hmac(
                    'sha256',
                    $upsert_payload['NIO'] . '|' . 
                        $upsert_payload['CABANG'] . '|' .
                        $upsert_payload['UNIT_KERJA'] . '|' .
                        $upsert_payload['JENIS_JABATAN'] . '|' .
                        $upsert_payload['ALAMAT'] . '|' .
                        $upsert_payload['KOTA'] . '|' .
                        $upsert_payload['TGL_MASUK'] . '|' .
                        $upsert_payload['BLN_BERAKHIR'] . '|' .
                        $upsert_payload['TGL_LAHIR'] . '|' .
                        $upsert_payload['TEMPAT_LAHIR'] . '|' .
                        $upsert_payload['STAT_AKTIF'] . '|' .
                        $upsert_payload['JNS_KELAMIN'] . '|' .
                        $upsert_payload['STAT_KAWIN'] . '|' .
                        $upsert_payload['NAMA_PERUSAHAAN'] . '|' .
                        $upsert_payload['NO_SK'] . '|' .
                        $upsert_payload['PENDIDIKAN'] . '|' .
                        $upsert_payload['REKENING'] . '|' .
                        $upsert_payload['STKANTOR'] . '|' .
                        $upsert_payload['TGL_PERSETUJUAN'] . '|' .
                        $upsert_payload['PENEMPATAN'] . '|' .
                        $upsert_payload['KETERANGAN'] . '|' .
                        $upsert_payload['AGAMA'] . '|' .
                        $upsert_payload['NOHP'],
                    'jatim',
                    true
                ));
                $upsert_tad_response = Http::withHeaders(
                    [
                        'Authorization' => 'Basic ' . base64_encode('prgm:pragmainformatika')
                    ]
                )
                    ->post('https://sriwijaya.bankjatim.co.id/EHC_SURROUNDING/rest/ETAD/' . (count($inquery_tad_response_json['ListTAD']) === 1 ? 'InsertTAD' : 'UpdateTAD'), $upsert_payload);
                // dd(331, $upsert_tad_response);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }
}
