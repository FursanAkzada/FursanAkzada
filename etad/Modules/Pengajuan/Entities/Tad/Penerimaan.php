<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use App\Entities\Traits\Approval;
use App\Entities\Traits\HasFiles;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Tad\DetailKandidat;
use Modules\Pengajuan\Entities\Tad\SummaryWawancara;
use Modules\Pengajuan\Notifications\Notify;

class Penerimaan extends BaseModel
{
    use HasFactory, Approval, HasFiles;

    protected $table = 'trans_pengajuan_tad_penerimaan';
    protected $fillable = [
        'wawancara_id',
        'nio',
        'no_sk',
        'status',
        'keputusan',
        'tgl_keputusan',
        'tgl_contractdue',
        'start_date_contract',
        'penerimaan_filename',
        'penerimaan_filepath',
        'pembukaan',
        'isi_surat',
        'penutup',
    ];

    protected $dates = ['tgl_keputusan', 'tgl_contractdue', 'start_date_contract'];

    public function setTglKeputusanAttribute($value)
    {
        if ($value) {
            // dd($value, request()->all());
        }
        try {
            $this->attributes['tgl_keputusan'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function setTglContractdueAttribute($value)
    {
	if ($value) {
            // dd($value, request()->all());
        }
        try {
	    $this->attributes['tgl_contractdue'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function setStartDateContractAttribute($value)
    {
	if ($value) {
            // dd($value, request()->all());
        }
        try {
	    $this->attributes['start_date_contract'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function wawancara()
    {
        return $this->belongsTo(SummaryWawancara::class, 'wawancara_id');
    }

    public function updateByRequest($request)
    {
        DB::beginTransaction();
        try {
            $data = 'Penerimaan TAD | Nama Kandidat ' . $this->wawancara->kandidat->tad->nama;

            // cek validasi nio
            if ($request->keputusan === 'Diterima') {
                $temps_nio = Kepegawaian::pluck('nio')->toArray();
                if (in_array($request->nio, $temps_nio)) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'NIO sudah ada sebelumnya!',
                            'trace' => null,
                        ],
                        422
                    );
                }
            }
            $tgl_keputusan      = (int) implode('', array_reverse(explode('/', $this->tgl_keputusan)));
            $tgl_contractdue    = (int) implode('', array_reverse(explode('/', $this->tgl_contractdue)));
            if ($tgl_contractdue < $tgl_keputusan) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Tgl Mulai Kontrak harus lebih dari/sama dengan Tgl Keputusan',
                        'trace' => null,
                    ],
                    422
                );
            }
            $this->nio = $request->nio;
            $this->fill($request->all());
            $this->save();

            $this->saveFilesByTemp($request->uploads, $request->module);

            if ($request->is_submit == 1) {
                if ($request->keputusan === 'Diterima') {
                    $requirement = $this->wawancara->kandidat->summary->requirement->jumlah;
                    $requirement_id = $this->wawancara->kandidat->summary->requirement->id;
                    $diterima = DetailKandidat::with('summary')
                        ->whereHas(
                            'summary',
                            function ($query) use ($requirement_id) {
                                $query->where('requirement_id', $requirement_id);
                            }
                        )->where('status', 'diterima')->get();

                    if ($diterima->count() === $requirement) {
                        return response()->json(
                            [
                                'success' => false,
                                'message' => 'Kandidat diterima sudah terpenuhi sesuai kebutuhan',
                                'trace' => null,
                            ],
                            422
                        );
                    }
                }
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
                'data'    => $this,
                'redirect' => route($request->route . '.index'),
            ]
        );
    }

    public function handleApproveSave($request)
    {
        DB::beginTransaction();
        try {
            $this->module = null;
            $data = 'Penerimaan TAD | Nama Kandidat ' . $this->wawancara->kandidat->tad->nama;

            // cek validasi nio
            //  if ($this->keputusan === 'Diterima'){
            //     $temps_nio = Kepegawaian::pluck('nio')->toArray();
            //     if (in_array($request->nio, $temps_nio)){
            //         return response()->json(
            //             [
            //                 'success' => false,
            //                 'message' => 'NIO sudah ada sebelumnya!',
            //                 'trace' => null,
            //             ],
            //             422
            //         );
            //     }
            // }

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
                'title' => in_array($this->status, ['completed']) ? 'Revisi ' : '' . 'Penerimaan TAD | Nama Kandidat ' . $this->wawancara->kandidat->tad->nama,
                'from' => $approval_user->id,
                'link' => route('pengajuan.penerimaan.approval', $this->id),
                'message' => '',
                'content' => $this
            ];
            if ($is_approved) {
                // Check Approval
                if ($this->approvals()->whereIn('status', ['draft', 'rejected', NULL])->count() == 0) {
                    if ($this->status == 'waiting.approval.upgrade') {
                        $this->update([
                            'version'   => $this->version + 1,
                            'status'    => 'draft',
                        ]);
                    } else {
                        $this->update(['status' => 'completed']);
                        $this->wawancara->kandidat->summary->requirement->pengajuan->pivotTad()->detach();
                        $this->wawancara->kandidat->summary->requirement->pengajuan->updated_at = now();
                        $this->wawancara->kandidat->summary->requirement->pengajuan->save();
                        if ($this->keputusan === 'Diterima') {
                            // pengecekan lagi untuk kuota terpenuhi
                            $requirement = $this->wawancara->kandidat->summary->requirement->jumlah;
                            $requirement_id = $this->wawancara->kandidat->summary->requirement->id;
                            $diterima = DetailKandidat::with('summary')
                                ->whereHas(
                                    'summary',
                                    function ($query) use ($requirement_id) {
                                        $query->where('requirement_id', $requirement_id);
                                    }
                                )->where('status', 'diterima')->get();

                            if ($diterima->count() == $requirement) {
                                return response()->json(
                                    [
                                        'success' => false,
                                        'message' => 'Kandidat diterima sudah terpenuhi sesuai kebutuhan',
                                        'trace' => null,
                                    ],
                                    422
                                );
                            }
                            $this->wawancara->kandidat->status = 'diterima';
                            $this->wawancara->kandidat->save();
                            // Kepegawaian
                            $kepegawaian = Kepegawaian::where('tad_id', $this->wawancara->kandidat->tad_id)
                                ->where('cabang_id', $this->wawancara->kandidat->summary->requirement->pengajuan->so_id)
                                ->where('kandidat_id', $this->wawancara->kandidat->id)
                                ->first();
                            if ($kepegawaian) {
                                $kepegawaian->in_at         = $request->start_date_contract;
                                $kepegawaian->contract_due  = $this->tgl_contractdue;
                                $kepegawaian->jenis_jabatan = '';
                            } else {
                                $kepegawaian = new Kepegawaian(
                                    [
                                        'status'        => Kepegawaian::RECRUITMENT,
                                        'tad_id'        => $this->wawancara->kandidat->tad_id,
                                        'vendor_id'     => $this->wawancara->kandidat->tad->vendor_id,
                                        'jabatan_id'    => $this->wawancara->kandidat->summary->requirement->jabatan_id,
                                        'cabang_id'     => $this->wawancara->kandidat->summary->requirement->pengajuan->so_id,
                                        'kandidat_id'   => $this->id,
                                        'nio'           => $this->nio,
                                        'in_at'         => $this->start_date_contract->format('d/m/Y'),
                                        'contract_due'  => $this->tgl_contractdue->format('d/m/Y'),
                                        'jenis_jabatan' => ''
                                    ]
                                );
                            }
                            // $kepegawaian->no_sk         = $this->no_sk;
                            $kepegawaian->no_sk         = $this->wawancara->kandidat->summary->requirement->pengajuan->no_tiket;
                            $kepegawaian->year          = $this->wawancara->kandidat->summary->pengajuan->year;
                            $kepegawaian->semester      = $this->wawancara->kandidat->summary->pengajuan->semester;
                            $kepegawaian->save();

                            $quota_periode = QuotaPeriode::where('year', $this->wawancara->kandidat->summary->pengajuan->year)
                                ->where('semester', $this->wawancara->kandidat->summary->pengajuan->semester)
                                ->where('level', OrgStruct::find($this->wawancara->kandidat->summary->requirement->pengajuan->so_id)->level)
                                ->first();
                            $quota_periode->fulfillment += 1;
                            $quota_periode->save();

                            $quota = Quota::where('pengajuan_tad_quota_periode_id', $quota_periode->id)
                                ->where('org_struct_id', $this->wawancara->kandidat->summary->requirement->pengajuan->so_id)
                                ->where('posisi_tad_id', $this->wawancara->kandidat->summary->requirement->jabatan_id)
                                ->first();
                            $quota->fulfillment += 1;
                            $quota->save();

                            $tad = Tad::find($this->wawancara->kandidat->tad_id);
                            $tad->kepegawaian_id = $kepegawaian->id;
                            $tad->save();

                            $this->_syncToEhc($tad, $kepegawaian);
                        }
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

    /** OTHER FUNCTIONS **/
    private function _syncToEhc($tad, $kepegawaian)
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
            )
                ->withoutVerifying()
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
                    "KOTA"              => $tad->city->name ?? null,
                    "TGL_MASUK"         => $kepegawaian->in_at->format('Y-m-d h:i:s'),
                    "BLN_BERAKHIR"      => $kepegawaian->contract_due->format('m'),
                    "TGL_LAHIR"         => $tad->tanggal_lahir->format('Y-m-d h:i:s'),
                    "TEMPAT_LAHIR"      => $tad->tempat_lahir,
                    "STAT_AKTIF"        => "1",
                    "JNS_KELAMIN"       => $tad->jenis_kelamin,
                    "STAT_KAWIN"        => $tad->status_perkawinan === "Menikah" ? '1' : ($tad->status_perkawinan === "Lajang" ? 0 : 2),
                    "NAMA_PERUSAHAAN"   => $kepegawaian->vendor->nama,
                    // "NO_SK"             => $this->wawancara->kandidat->summary->requirement->pengajuan->no_tiket,
                    "NO_SK"             => $this->no_sk,
                    "PENDIDIKAN"        => $tad->pendidikan_id,
                    "REKENING"          => $tad->rekening_bjtm,
                    "STKANTOR"          => $kepegawaian->unitKerja->level === 'cabang' ? "CB" : 'KP',
                    "TGL_PERSETUJUAN"   => now()->format('Y-m-d h:i:s'),
                    "PENEMPATAN"        => $kepegawaian->unitKerja->name,
                    "KETERANGAN"        => "Penerimaan TAD",
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
                    ->withoutVerifying()
                    ->post('https://sriwijaya.bankjatim.co.id/EHC_SURROUNDING/rest/ETAD/' . (count($inquery_tad_response_json['ListTAD']) === 1 ? 'InsertTAD' : 'UpdateTAD'), $upsert_payload);

                if ($tad->telepon) {
                    $watsap = [
                        "NIO"               => $kepegawaian->nio,
                        "Nama"              => $tad->nama,
                        "NoHp"              => $tad->telepon,
                        "NoPeserta"         => $this->id,
                        "Vendor"   => $kepegawaian->vendor->nama,
                    ];
                    $watsap['Signature'] = base64_encode(hash_hmac(
                        'sha256',
                        $watsap['NIO'] . '|' .
                            $watsap['Nama'] . '|' .
                            $watsap['NoHp'] . '|' .
                            $watsap['NoPeserta'] . '|' .
                            $watsap['Vendor'],
                        'jatim',
                        true
                    ));
                    $watsap_response = Http::withHeaders(
                        [
                            'Authorization' => 'Basic ' . base64_encode('prgm:pragmainformatika')
                        ]
                    )
                        ->withoutVerifying()
                        ->post('https://sriwijaya.bankjatim.co.id/EHC_SURROUNDING/rest/ETAD/SendWaConfirm', $watsap);
                }

                if ($tad->email) {
                    $email = [
                        "NIO"       => $kepegawaian->nio,
                        "Nama"      => $tad->nama,
                        "Email"     => $tad->email,
                        "NoPeserta" => $this->id,
                        "Vendor"    => $kepegawaian->vendor->nama,
                        "Subject"   => 'DITERIMA TAD',
                    ];
                    $email['Signature'] = base64_encode(hash_hmac(
                        'sha256',
                        $email['NIO'] . '|' .
                            $email['Nama'] . '|' .
                            $email['Email'] . '|' .
                            $email['NoPeserta'] . '|' .
                            $email['Vendor'] . '|' .
                            $email['Subject'],
                        'jatim',
                        true
                    ));
                    $email_response = Http::withHeaders(
                        [
                            'Authorization' => 'Basic ' . base64_encode('prgm:pragmainformatika')
                        ]
                    )
                        ->withoutVerifying()
                        ->post('https://sriwijaya.bankjatim.co.id/EHC_SURROUNDING/rest/ETAD/SendMailConfirm', $email);
                }
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }
    public function saveLogNotifyApproval($request)
    {
        $notify = [
            'id' => $this->id,
            'type' => $request->module,
            'title' => in_array($this->status, ['waiting.approval.upgrade', 'waiting.approval.revisi', 'draft']) ? 'Revisi ' : '' . 'Penerimaan TAD | Nama Kandidat ' . $this->wawancara->kandidat->tad->nama,
            'from' => auth()->id(),
            'link' => route('pengajuan.penerimaan.approval', $this->id),
            'message' => 'Penerimaan TAD membutuhkan persetujuan',
            'content' => $this
        ];

        $checkFirstApprover = $this->approval($request->module)
                ->where('is_upgrade', false)
                ->whereStatus('draft')
                ->orderBy('order')
                ->whereIn('group_id', [6])
                ->first();
        if(!empty($checkFirstApprover)){
            if(!empty($this->wawancara->kandidat->summary->pengajuan->so->level)){
                switch ($this->wawancara->kandidat->summary->pengajuan->so->level) {
                    case 'division':
                    case 'cabang':
                        $approval = $this->firstNewApprovalGet($request->module);
                        $userCabang = User::whereHas('position', function($q){
                            $q->where('org_struct_id',  $this->wawancara->kandidat->summary->pengajuan->so_id);
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
                            $q->where('org_struct_id',  $this->wawancara->kandidat->summary->pengajuan->so->parent_id);
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
    public function getFileIconAttribute($value)
    {
        $icon = 'far fa-file-alt';
        $type =  preg_replace('/^.*\.([^.]+)$/D', '$1', $value);
        if ($type === 'pdf') {
            $icon = 'text-danger far fa-file-pdf';
        } else if ($type === 'xlsx') {
            $icon = 'text-success far fa-file-excel';
        } else if ($type === 'jpg' || $type === 'png') {
            $icon = 'text-warning far fa-file-image';
        } else if ($type === 'ppt') {
            $icon = 'text-danger far fa-file-powerpoint';
        } else if ($type === 'docx') {
            $icon = 'text-primary far fa-file-word';
        }
        return $icon . ' ' . $type;
    }

    public function getPembukaan()
    {
        return "<p>Terkait dengan pemenuhan kekosongan tenaga alih daya pada " . $this->wawancara->kandidat->summary->pengajuan->so->name .
            " sesuai dengan surat no. " . $this->wawancara->kandidat->summary->pengajuan->no_tiket . " tanggal " . $this->wawancara->kandidat->summary->pengajuan->tgl_pengajuan->translatedFormat('d F Y') . " perihal Penerimaan Tenaga Alih Daya (TAD), maka dengan ini disampaikan bahwa:
        </p>";
    }

    public function getIsiSurat()
    {
        return "";
    }

    public function getPenutup()
    {
        return "Demikian untuk dijadikan pedoman dan pelaksanaan.";
    }
}
