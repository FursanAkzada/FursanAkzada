<?php

namespace Modules\Penilaian\Entities\Tad;

use App\Entities\BaseModel;
use App\Entities\EHC\Jabatan;
use App\Entities\Traits\Approval;
use App\Entities\Traits\HasFiles;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Http;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\SO\Positions;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Tad\Quota;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;
use Modules\Pengajuan\Notifications\Notify;


class Perpanjangan extends BaseModel
{
    use HasFactory, Approval, HasFiles;

    protected $table    = 'trans_perpanjangan_tad';
    protected $fillable = [
        'no_pengajuan',
        'tgl_pengajuan',
        'unit_kerja_id',
        'to',
        'keterangan',
        'tad_id',
        'kepegawaian_id',
        'tgl_akhir_kontrak_lama',
        'tgl_awal_kontrak_baru',
        'tgl_akhir_kontrak_baru',
        'pembukaan',
        'penutup',
        'status',
	'personil'
    ];
    protected $casts = [
        'tgl_pengajuan'             => 'date',
        'tgl_akhir_kontrak_lama'    => 'date',
        'tgl_awal_kontrak_baru'     => 'date',
        'tgl_akhir_kontrak_baru'    => 'date',
    ];

    public function setTglPengajuanAttribute($value)
    {
        try {
            $this->attributes['tgl_pengajuan'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function setTglAwalKontrakBaruAttribute($value)
    {
        try {
            $this->attributes['tgl_awal_kontrak_baru'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function setTglAkhirKontrakBaruAttribute($value)
    {
        try {
            $this->attributes['tgl_akhir_kontrak_baru'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function setTglAkhirKontrakLamaAttribute($value)
    {
        try {
            $this->attributes['tgl_akhir_kontrak_lama'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function unitKerja()
    {
        return $this->belongsTo(OrgStruct::class, 'unit_kerja_id');
    }

    public function kepegawaian()
    {
        return $this->belongsTo(Kepegawaian::class, 'kepegawaian_id');
    }
    public function tad()
    {
        return $this->belongsTo(Tad::class, 'tad_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to');
    }

    public function cc()
    {
        return $this->belongsToMany(Positions::class, PerpanjanganCC::class, 'pengajuan_id', 'user_id');
    }

    public function ccHasMany()
    {
        return $this->hasMany(PerpanjanganCC::class, 'pengajuan_id');
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

    public static function createOrUpdateByRequest($request, $record)
    {
        try {
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

	    $record->fill($request->all());
	    $record->personil = $record->tad->nama;
	    $record->save();
            $kepegawaian = Kepegawaian::find($tad_check->kepegawaian_id);

            $tahun_awal      = Carbon::createFromFormat('d/m/Y', $request->tgl_awal_kontrak_baru)->format('Y');
            $bulan_awal      = Carbon::createFromFormat('d/m/Y', $request->tgl_awal_kontrak_baru)->format('m');
            $tanggal_awal    = Carbon::createFromFormat('d/m/Y', $request->tgl_awal_kontrak_baru)->format('d');
            $tahun_akhir     = Carbon::createFromFormat('d/m/Y', $request->tgl_akhir_kontrak_baru)->format('Y');
            $bulan_akhir     = Carbon::createFromFormat('d/m/Y', $request->tgl_akhir_kontrak_baru)->format('m');
            $tanggal_akhir   = Carbon::createFromFormat('d/m/Y', $request->tgl_akhir_kontrak_baru)->format('d');

            if (($tahun_awal < $tahun_akhir) || ($tahun_awal === $tahun_akhir && $bulan_awal < 7 && $bulan_akhir > 6)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tgl Akhir Kontrak Baru harus di periode yang sama dengan Tgl Awal Kontrak Baru',
                    'trace' => null,
                ], 422);
            }

            $semester = Carbon::createFromFormat('d/m/Y', $request->tgl_awal_kontrak_baru)->format('m') <= 6 ? 'Satu' : 'Dua';
            $quota = Quota::whereHas(
                'periode',
                function ($q) use ($request, $kepegawaian, $tahun_awal, $semester) {
                    $q
                        ->where('level', $kepegawaian->unitKerja->level)
                        ->where('year', $tahun_awal)
                        ->where('semester', $semester);
                }
            )->where([
                ['org_struct_id', $kepegawaian->cabang_id],
                ['posisi_tad_id', $kepegawaian->jabatan_id]
            ])->first();

            if ($quota == NULL) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Posisi TAD ' . Jabatan::find($kepegawaian->jabatan_id)->NM_UNIT . ' pada unit kerja tidak memiliki kuota pada periode ini!',
                        'trace' => '',
                    ],
                    422
                );
            }

            $record->kepegawaian_id = $tad_check->kepegawaian_id;
            if ($tad_check->kepegawaian->contract_due) {
                $record->tgl_akhir_kontrak_lama = $tad_check->kepegawaian->contract_due->format('d/m/Y');
            }
            $record->update();
            $record->saveCc($request->cc ?? []);

            $record->saveFilesByTemp($request->uploads, $request->module, 'lampiran');

            $data = 'Perpanjangan Kontrak TAD : ' . $request->no_pengajuan . ', ' . $tad_check->nama;

            if ($request->is_submit == 1) {
                $record->status = $request->is_submit == 1 ? 'waiting.approval' : 'draft';
		$record->personil = $record->tad->nama;
                $record->save();
                $record->generateApproval($request->module);
                $record->saveLogNotifyApproval($request);

                $record->addLog('Submit Data ' . $data);
                auth()->user()->storeLog($request->module, 'submit');
            } else {
                $record->status = 'draft';
                $record->save();
                if (isset($record->status)) {
                    $record->addLog('Mengubah Data ' . $data);
                    auth()->user()->storeLog($request->module, 'update');
                } else {
                    $record->addLog('Membuat Data ' . $data);
                    auth()->user()->storeLog($request->module, 'create');
                }
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

    public function deleteByRequest($request)
    {
        \DB::beginTransaction();
        try {
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

    public function handleApproveSave($request, $record)
    {
        \DB::beginTransaction();
        try {
            $record->module = null;
            $data = 'Perpanjangan TAD | Nama Kandidat ' . $record->tad->nama;

            $approval_user = auth()->user();
            $is_approved = $request->is_submit == 'approved' ? true : false;
            $old_data = json_encode($record->load('approvals'));
            $this->touch();

            // Save Approval
            $approval = $record->approvals()->find($request->approval_id);
            $approval->status          = $is_approved ? 'approved' : 'rejected';
            $approval->keterangan      = $is_approved ? null : $request->keterangan;
            $approval->approved_at     = $is_approved ? now() : null;
            $approval->position_id     = auth()->user()->position_id;
            $approval->user_id         = auth()->id();
            $approval->save();

            $notify = [
                'id' => $record->id,
                'type' => $request->module,
                'title' => 'Perpanjangan Kontrak TAD : ' . $record->no_pengajuan . ', ' . $record->tad->nama,
                'from' => auth()->id(),
                'link' => route('penilaian.perpanjangan.approval', $record->id),
                'message' => '',
                'content' => $record
            ];
            // dd($this->tgl_awal_kontrak_baru->format('d/m/Y'));
            if ($is_approved) {
                // Check Approval
                if ($record->approvals()->whereIn('status', ['draft', 'rejected'])->count() == 0) {
                    if ($this->status == 'waiting.approval.upgrade') {
                        $this->update([
                            'version'   => $this->version + 1,
                            'status'    => 'draft',
                        ]);
                    } else {
                        // dd($this->tgl_awal_kontrak_baru->format('d/m/Y') . $this->tgl_akhir_kontrak_baru->format('d/m/Y'));
                        $this->update(['status' => 'completed']);
                        $kepegawaian = Kepegawaian::find($this->kepegawaian_id);

                        $new_kepegawaian = new Kepegawaian;
                        $new_kepegawaian->status        = Kepegawaian::EXTENSION;
                        $new_kepegawaian->previous_employment_id    = $kepegawaian->id;
                        $new_kepegawaian->year          = $this->tgl_awal_kontrak_baru->format('Y');
                        $new_kepegawaian->semester      = $this->tgl_awal_kontrak_baru->format('m') <= 6 ? 'Satu' : 'Dua';
                        $new_kepegawaian->tad_id        = $kepegawaian->tad_id;
                        $new_kepegawaian->cabang_id     = $kepegawaian->cabang_id;
                        $new_kepegawaian->vendor_id     = $kepegawaian->vendor_id;
                        $new_kepegawaian->jabatan_id    = $kepegawaian->jabatan_id;
                        $new_kepegawaian->nio           = $kepegawaian->nio;
                        $new_kepegawaian->no_sk         = $kepegawaian->no_sk;
                        $new_kepegawaian->in_at         = $this->tgl_awal_kontrak_baru->format('d/m/Y');
                        $new_kepegawaian->contract_due  = $this->tgl_akhir_kontrak_baru->format('d/m/Y');
                        // $kepegawaian->contract_due  = null;
                        // $kepegawaian->in_at         = null;
                        $new_kepegawaian->save();
                        // $kepegawaianBaru = Kepegawaian::firstOrNew(['id' => $this->kepegawaian_id]);
                        // $kepegawaianBaru->contract_due  = $this->tgl_akhir_kontrak_baru->format('d/m/Y');
                        // $kepegawaianBaru->in_at         = $this->tgl_awal_kontrak_baru->format('d/m/Y');
                        // $kepegawaianBaru->save();

                        $tad = Tad::find($this->tad_id);
                        $tad->kepegawaian_id = $new_kepegawaian->id;
                        $tad->save();

                        // KURANGI PEMENUHAN QUOTA LAMA
                        $quota_periode_lama = QuotaPeriode::where('level', $kepegawaian->unitKerja->level)
                            ->where('year', $kepegawaian->year)
                            ->where('semester', $kepegawaian->semester == 'Dua' ? 'Dua' : 'Satu')
                            ->first();

                        $quota_periode_lama->fulfillment -= 1;
                        $quota_periode_lama->save();
                        $quota_lama = Quota::where('pengajuan_tad_quota_periode_id', $quota_periode_lama->id)
                            ->where('org_struct_id', $kepegawaian->cabang_id)
                            ->where('posisi_tad_id', $kepegawaian->jabatan_id)
                            ->first();
                        $quota_lama->fulfillment -= 1;
                        $quota_lama->save();

                        // TAMBAH PEMENUHAN QUOTA BARU
                        $quota_periode_baru = QuotaPeriode::where('level', $kepegawaian->unitKerja->level)
                            ->where('year', $new_kepegawaian->year)
                            ->where('semester', $new_kepegawaian->semester)
                            ->first();
                        $quota_periode_baru->fulfillment += 1;
                        $quota_periode_baru->save();
                        $quota_baru = Quota::where('pengajuan_tad_quota_periode_id', $quota_periode_baru->id)
                            ->where('org_struct_id', $new_kepegawaian->cabang_id)
                            ->where('posisi_tad_id', $new_kepegawaian->jabatan_id)
                            ->first();
                        $quota_baru->fulfillment += 1;
                        $quota_baru->save();

                        $this->_syncToEhc($tad, $new_kepegawaian);
                    }
                    // $notify['message'] = 'Proses Perpanjangan Kontrak telah selesai.';
                    // $this->creator->notify(new Notify($notify));
                }
                $record->saveLogNotifyApproval($request);
                $record->addLog('Menyetujui Data ' . $data);
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

            $new_data = json_encode($record->load('approvals'));
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
            // dd(370, $inquiry_payload, $inquery_tad_response_json);
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
                    "BLN_BERAKHIR"      => $kepegawaian->contract_due->format('m'),
                    "TGL_LAHIR"         => $tad->tanggal_lahir->format('Y-m-d h:i:s'),
                    "TEMPAT_LAHIR"      => $tad->tempat_lahir,
                    "STAT_AKTIF"        => "1",
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
                    "KETERANGAN"        => "Perpanjangan",
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
                // dd(json_decode($kepegawaian));
                throw $th;
            }
        }
    }
    public function saveLogNotifyApproval($request)
    {
        $notify = [
            'id' => $this->id,
            'type' => $request->module,
            'title' => in_array($this->status, ['waiting.approval.upgrade', 'waiting.approval.revisi', 'draft']) ? 'Revisi ' : '' . 'Perpanjangan Kontrak TAD : ' . $this->no_pengajuan . ', ' . $this->tad->nama,
            'from' => auth()->id(),
            'link' => route('penilaian.perpanjangan.approval', $this->id),
            'message' => 'Perpanjangan TAD membutuhkan persetujuan',
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
