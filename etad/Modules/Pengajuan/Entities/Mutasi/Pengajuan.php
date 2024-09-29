<?php

namespace Modules\Pengajuan\Entities\Mutasi;

use App\Entities\BaseModel;
use App\Entities\EHC\Cabang;
use App\Entities\EHC\Jabatan;
use App\Entities\Traits\Approval;
use App\Entities\Traits\HasFiles;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\SO\Positions;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Tad\Quota;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;
use Modules\Pengajuan\Notifications\Notify;
use Modules\Settings\Entities\Menu;

class Pengajuan extends BaseModel
{
    use HasFactory, Approval, HasFiles;

    protected $table = 'trans_pengajuan_mutasi';
    protected $fillable = [
        'status',
        'no_tiket',
        'no',
        'perihal',
        'menunjuk',
        'menindaklanjuti',
        'unit_kerja_asal',
        'unit_kerja_tujuan',
        'tgl_mutasi',
        'tgl_pengajuan',
        'sk',
        'jenis_mutasi',
        'sk_filepath',
        'sk_filename',
	'personil'
    ];

    protected $dates = ['tgl_pengajuan'];

    public function setTglPengajuanAttribute($value)
    {
        $this->attributes['tgl_pengajuan'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }

    public function logs()
    {
        return $this->hasMany(Logs::class, 'pengajuan_id');
    }

    public function cc()
    {
        // return $this->belongsToMany(User::class, CC::class, 'pengajuan_id', 'user_id');
        return $this->belongsToMany(Positions::class, CC::class, 'pengajuan_id', 'user_id');
    }
    public function to()
    {
        return $this->belongsToMany(Positions::class, 'trans_pengajuan_mutasi_to', 'trans_id', 'user_id');
    }
    public function ccHasMany()
    {
        return $this->hasMany(CC::class, 'pengajuan_id');
    }

    public function details()
    {
        return $this->hasMany(Pegawai::class, 'pengajuan_id');
    }

    public function pegawai()
    {
        return $this->belongsToMany(Tad::class, 'trans_pengajuan_mutasi_pegawai', 'pengajuan_id', 'tad_id')
            ->withPivot('id', 'kepegawaian_id', 'tgl_mutasi', 'tgl_efektif');
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

    public function unitKerjaAsal()
    {
        return $this->belongsTo(OrgStruct::class, 'unit_kerja_asal');
    }
    public function unitKerjaTujuan()
    {
        return $this->belongsTo(OrgStruct::class, 'unit_kerja_tujuan');
    }
    public function show_tgl_pengajuan()
    {
        return Carbon::createFromFormat('d/m/Y', $this->tgl_pengajuan);
    }

    public function generateTicketNumber()
    {
        $last = $this->orderBy('id', 'desc')->first();
        $lastNumber = $last ? substr($last->no_tiket, -3) : 000;
        return 'MTSI-' . Carbon::now()->format('dmy') . str_pad($lastNumber + 1, 3, 0, STR_PAD_LEFT);
    }

    public static function createByRequest($request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();

            // validasi
            $list_posisi_tad = [];
            foreach ($request->pegawai as $key => $item) {
                $list_posisi_tad[] = Tad::find($item['id'])->jabatan_id;
            }
            $unique_posisi_tad = array_unique($list_posisi_tad);

            $tahun = Carbon::createFromFormat('d/m/Y', $request->tgl_pengajuan)->format('Y');
            $semester = Carbon::createFromFormat('d/m/Y', $request->tgl_pengajuan)->format('m');
            $semester = ($semester <= 6) ? 'Satu' : 'Dua';

            foreach ($unique_posisi_tad as $posisi_tad_id) {
                $quota_available = 0;

                $quota = Quota::whereHas(
                    'periode',
                    function ($q) use ($request, $tahun, $semester) {
                        $q
                            // ->where('status', 'completed')
                            ->where('year', $tahun)
                            ->where('semester', $semester);
                    }
                )->where([
                    ['org_struct_id', $request->unit_kerja_tujuan],
                    ['posisi_tad_id', $posisi_tad_id]
                ])->first();

                if ($quota == NULL) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Posisi TAD ' . Jabatan::find($posisi_tad_id)->NM_UNIT . ' pada unit kerja tujuan tidak memiliki kuota pada periode ini!',
                            'trace' => '',
                        ],
                        422
                    );
                }

                // Count how many times a specific posisi_tad_id appears in the list
                $countOfPosisiTad = array_count_values($list_posisi_tad)[$posisi_tad_id] ?? 0;

                $quota_available = $quota->quota - $quota->fulfillment - $countOfPosisiTad;

                if ($quota_available < 0) {
                    return response()->json(
                        [
                            'success' => false,
                            // 'message' => 'Posisi TAD ' . Jabatan::find($posisi_tad_id)->NM_UNIT . ' sudah memenuhi kuota!',
                            'message' => 'Posisi TAD ' . Jabatan::find($posisi_tad_id)->NM_UNIT . ' pada unit kerja tujuan tidak memiliki kuota pada periode ini!',
                            'trace' => '',
                        ],
                        422
                    );
                }
            }

            $record = new Self;
            $data = $request->all();
            $record->no_tiket = (new Self)->generateTicketNumber();
            $record->tgl_pengajuan  = $request->tgl_pengajuan;
            $record->no = $request->no;
            $record->perihal = $request->perihal;
            $record->menunjuk = $request->menunjuk;
            $record->menindaklanjuti = $request->menindaklanjuti;
            $record->unit_kerja_asal = $request->unit_kerja_asal;
            $record->unit_kerja_tujuan = $request->unit_kerja_tujuan;
	    foreach ($request->pegawai as $key => $pers) {
                $record->personil = Tad::find($pers['id'])->nama;
	    }

            /* File Surat */
            // $record->status = $request->is_submit ? 'submit' : 'draft';
            $record->status = $request->is_submit == 1 ? 'waiting.approval' : 'draft';
            $record->save();
            $record->saveFilesByTemp($request->uploads, $request->module, 'surat', $record->id);
            $record->cc()->sync($request->user_id ?? []);
            $record->to()->sync($request->to ?? []);

            /* Save Pegawai */
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
                // $pegawai[$item['id']] = ['tanggal_mutasi' => Carbon::parse($item['tanggal_mutasi'])->format('Y-m-d')];
                // $record->personil = Tad::find($item['id'])->nama;
		$pegawai[$item['id']] = [
                    'pengajuan_id'      => $record->id,
                    'tad_id'            => $item['id'],
                    'kepegawaian_id'    => Tad::find($item['id'])->kepegawaian_id,
                    'tgl_mutasi'        => Carbon::createFromFormat('d/m/Y', $item['tanggal_mutasi'])->format('Y-m-d'),
                    'tgl_efektif'       => Carbon::createFromFormat('d/m/Y', $item['tanggal_efektif'])->format('Y-m-d'),
                    'created_at'        => now(),
                ];
            }
            $record->pegawai()->sync($pegawai);

            if ($request->is_submit == 1) {
                $record->generateApproval($request->module);
                foreach ($record->pegawai as $key => $pegawai) {
                    $personil_tad               = Tad::with('kepegawaian')->find($pegawai->id);
                    $personil_tad->lock_id      = $pegawai->pivot->id;
                    $personil_tad->lock_type    = Modules\Pengajuan\Entities\Mutasi\Pegawai::class;
                    $personil_tad->save();
                }
                $record->saveLogNotifyApproval($request);

                /* Save Logs */
                $record->createLogs('logs', 'Submit pengajuan', 0);
                auth()->user()->storeLog($request->module, 'create');
                auth()->user()->storeLog($request->module, 'submit');

                // CC
                $notify['message'] = auth()->user()->name . ' membuat Pengajuan Mutasi.';
                foreach (($record->cc ?? []) as $cc) {
                    foreach ($cc->users as $user) {
                        $user->notify(new Notify($notify));
                    }
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
                'data'    => $record,
                'redirectTo' => route('pengajuan.mutasi.index')
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
                // $pegawai[$item['id']] = ['tanggal_mutasi' => Carbon::parse($item['tanggal_mutasi'])->format('Y-m-d')];
                $this->personil = Tad::find($item['id'])->nama;
		$pegawai[$item['id']] = [
                    'pengajuan_id'      => $this->id,
                    'tad_id'            => $item['id'],
                    'kepegawaian_id'    => Tad::find($item['id'])->kepegawaian_id,
                    'tgl_mutasi'        => Carbon::createFromFormat('d/m/Y', $item['tanggal_mutasi'])->format('Y-m-d'),
                    'tgl_efektif'       => Carbon::createFromFormat('d/m/Y', $item['tanggal_efektif'])->format('Y-m-d'),
                ];
            }
            $this->pegawai()->sync($pegawai);

            // validasi
            $list_posisi_tad = [];
            foreach ($request->pegawai as $key => $item) {
                $list_posisi_tad[] = Tad::find($item['id'])->jabatan_id;
            }
            $unique_posisi_tad = array_unique($list_posisi_tad);

            $tahun = Carbon::createFromFormat('d/m/Y', $request->tgl_pengajuan)->format('Y');
            $semester = Carbon::createFromFormat('d/m/Y', $request->tgl_pengajuan)->format('m');
            $semester = ($semester <= 6) ? 'Satu' : 'Dua';

            foreach ($unique_posisi_tad as $posisi_tad_id) {
                $quota_available = 0;

                $quota = Quota::whereHas(
                    'periode',
                    function ($q) use ($request, $tahun, $semester) {
                        $q
                            // ->where('status', 'completed')
                            ->where('year', $tahun)
                            ->where('semester', $semester);
                    }
                )->where([
                    ['org_struct_id', $request->unit_kerja_tujuan],
                    ['posisi_tad_id', $posisi_tad_id]
                ])->first();

                if ($quota == NULL) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Posisi TAD ' . Jabatan::find($posisi_tad_id)->NM_UNIT . ' pada unit kerja tujuan tidak memiliki kuota pada periode ini!',
                            'trace' => '',
                        ],
                        422
                    );
                }

                // Count how many times a specific posisi_tad_id appears in the list
                $countOfPosisiTad = array_count_values($list_posisi_tad)[$posisi_tad_id] ?? 0;

                $quota_available = $quota->quota - $quota->fulfillment - $countOfPosisiTad;

                if ($quota_available < 0) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Posisi TAD ' . Jabatan::find($posisi_tad_id)->NM_UNIT . ' sudah memenuhi kuota!',
                            'trace' => '',
                        ],
                        422
                    );
                }
            }


            $data = $request->all();
            $data['tgl_pengajuan'] = $request->tgl_pengajuan;
            $data['status'] = $request->is_submit == 1 ? 'waiting.approval' : 'draft';
            // $data['status'] = $request->is_submit ? 'submit' : 'draft';
            $old = $this->replicate();
            $this->update($data);
            $this->cc()->sync($request->user_id ?? []);
	    $this->to()->sync($request->to ?? []);

            /* Save Pegawai */
            DB::table('trans_pengajuan_mutasi_pegawai')
                ->where('pengajuan_id', $this->i)
                ->delete();
           
            if ($request->is_submit == 1) {
                $this->generateApproval($request->module);
                foreach ($this->pegawai as $key => $pegawai) {
                    $personil_tad               = Tad::with('kepegawaian')->find($pegawai->id);
                    $personil_tad->lock_id      = $pegawai->pivot->id;
                    $personil_tad->lock_type    = Modules\Pengajuan\Entities\Mutasi\Pegawai::class;
                    $personil_tad->save();
                }
                $this->saveLogNotifyApproval($request);
                /* Save Logs */
                $this->createLogs('logs', 'Submit pengajuan', 0);
                auth()->user()->storeLog($request->module, 'update');
                auth()->user()->storeLog($request->module, 'submit');

                // $notify = [
                //     'id' => $this->id,
                //     'type' => $request->module,
                //     'title' => 'Pengajuan Mutasi TAD : ' . $this->no_tiket,
                //     'from' => auth()->id(),
                //     'link' => route('pengajuan.mutasi.show', $this->id),
                //     'message' => 'Pengajuan Mutasi TAD membutuhkan otorisasi',
                //     'content' => $this
                // ];
                // if ($menu = Menu::where('code', $request->module)->first()) {
                //     $menu->load('flows.group.users');
                //     $users = [];
                //     foreach ($menu->flows as $flow) {
                //         foreach ($flow->group->users as $user) {
                //             if (!in_array($user->id, $users)) {
                //                 /* Send Notification */
                //                 $user->notify(new Notify($notify));
                //                 $users[] = $user->id;
                //             }
                //         }
                //     }
                // }
                // CC
                $notify['message'] = auth()->user()->name . ' membuat Pengajuan Mutasi.';
                foreach ($this->cc as $cc) {
                    foreach ($cc->users as $user) {
                        $user->notify(new Notify($notify));
                    }
                }
            } else {
                /* Save Logs */
                $this->createLogs('draft', 'Data Tersimpan Sebagai Draft');
                auth()->user()->storeLog($request->module, 'update');
            }
            /* File Surat */
            $this->saveFilesByTemp($request->uploads, $request->module, 'surat', $this->id);

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
                'redirectTo' => route('pengajuan.mutasi.index'),
            ]
        );
    }
    public function deleteByRequest()
    {
        DB::beginTransaction();
        try {
            Storage::delete([$this->sk_filepath]);
            $this->pegawai()->detach();
            $this->delete();
            auth()->user()->storeLog(request()->module, 'delete');
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

    public function approvalByRequest($request)
    {
        DB::beginTransaction();
        try {
            $this->disableStatusBefore();
            $this->createLogs(
                $request->status,
                $request->status == 'approved.hc' ? 'Human Capital Menyetujui Pengajuan' : $request->keterangan,
                1
            );
            if ($request->status == 'approved.hc') {
                $this->tad()
                    ->update(
                        [
                            'jabatan_id' => $this->after_jabatan_id
                        ]
                    );
                Kepegawaian::where(
                    'tad_id',
                    $this->tad_id
                )->update(
                    [
                        'cabang_id' => $this->after_cabang_id
                    ]
                );
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

    public function jenisMutasiRaw()
    {
        switch ($this->jenis_mutasi) {
            case 1:
                return 'Atas Permintaan Sendiri';
            case 2:
                return 'Alih Tugas Produktif';
            case 3:
                return 'Tata Kelola Organisasi yg Baik';
            default:
                return '-';
        }
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
            $approval->status           = $is_approved ? 'approved' : 'rejected';
            $approval->keterangan       = $is_approved ? null : $request->keterangan;
            $approval->approved_at      = $is_approved ? now() : null;
            $approval->position_id      = auth()->user()->position_id;
            $approval->user_id          = auth()->id();
            $approval->save();

            $notify = [
                'id' => $this->id,
                'type' => 'resign_mutasi.mutasi',
                'title' => 'Pengajuan Mutasi : ' . $this->no_tiket,
                'from' => $approval_user->id,
                'link' => route('pengajuan.mutasi.show', $this->id),
                'message' => '',
                'content' => $this
            ];

            if ($is_approved) {
                $this->createLogs('logs', 'Menyetujui Pengajuan Mutasi', 0);
                // Check Approval
                if ($this->approvals()->whereIn('status', ['draft', 'rejected'])->count() == 0) {
                    $this->update(['status' => 'completed']);
                    $notify['message'] = 'Proses Persetujuan telah selesai.';
                    $this->creator->notify(new Notify($notify));

                    $this->load('pegawai.kepegawaian');
                    foreach ($this->pegawai as $key => $pegawai) {
                        $personil_tad               = Tad::with('kepegawaian')->find($pegawai->id);

                        $kepegawaian                = Kepegawaian::find($pegawai->kepegawaian_id);
                        $kepegawaian->out_at        = Carbon::parse($pegawai->pivot->tgl_efektif)->format('d/m/Y');
                        $kepegawaian->mutation_at   = Carbon::parse($pegawai->pivot->tgl_efektif)->format('d/m/Y');
                        $kepegawaian->save();

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
                            $new_kepegawaian->pengajuan_mutasi_pegawai_id   = $pegawai->pivot->id;
                            $new_kepegawaian->cabang_id                     = $this->unit_kerja_tujuan;
                            $new_kepegawaian->in_at                         = Carbon::parse($pegawai->pivot->tgl_efektif)->format('d/m/Y');
                            $new_kepegawaian->mutation_at                   = Carbon::parse($pegawai->pivot->tgl_efektif)->format('d/m/Y');
                            $new_kepegawaian->status                        = Kepegawaian::MUTATION;				
			    $new_kepegawaian->save();
                            
			    $personil_tad->kepegawaian_id   = $new_kepegawaian->id;
                            $personil_tad->save();

                            $pegawai->pivot->kepegawaian_id = $new_kepegawaian->id;
                            $pegawai->pivot->save();
                       }
		       // KURANGI PEMENUHAN QUOTA LAMA
                       $quota_periode_lama = QuotaPeriode::where('level', $this->unitKerjaAsal->level)
                            ->where('year', $kepegawaian->year ?? now()->format('Y'))
                            ->where('semester', $kepegawaian->semester == 'Dua' ? 'Dua' : 'Satu')
                            ->first();
                       $quota_periode_lama->fulfillment;
                       $quota_periode_lama->save();

                       $quota_lama = Quota::where('pengajuan_tad_quota_periode_id', $quota_periode_lama->id)
                            ->where('org_struct_id', $kepegawaian->cabang_id)
                            ->where('posisi_tad_id', $kepegawaian->jabatan_id)
                            ->first();
                       $quota_lama->fulfillment -= 1;
                       $quota_lama->save();

                       // TAMBAH PEMENUHAN QUOTA BARU
                       $quota_periode_baru = QuotaPeriode::where('level', $this->unitKerjaTujuan->level)
                           ->where('year', now()->format('Y'))
                           ->where('semester', now()->format('m') <= 6 ? 'Satu' : 'Dua')
                           ->first();
                       $quota_periode_baru->fulfillment;
                       $quota_periode_baru->save();

                       $quota_baru = Quota::where('pengajuan_tad_quota_periode_id', $quota_periode_baru->id)
                           ->where('org_struct_id', $new_kepegawaian->cabang_id)
                           ->where('posisi_tad_id', $new_kepegawaian->jabatan_id)
                           ->first();
                       $quota_baru->fulfillment;
                       $quota_baru->save();

                       // $personil_tad            = Kepegawaian::find($pegawai->id);
                       // $personil_tad->cabang_id = $this->unit_kerja_tujuan;
                       // $personil_tad->save();
                    }
                    Artisan::call('push:personil-mutasi');
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
                'redirectTo' => route('pengajuan.mutasi.index'),
            ]
        );
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
                    "KETERANGAN"        => "Mutasi",
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
            'title' => 'Pengajuan Mutasi : ' . $this->no_tiket,
            'from' => auth()->id(),
            'link' => route('pengajuan.mutasi.show', $this->id),
            'message' => 'Pengajuan Mutasi membutuhkan persetujuan',
            'content' => $this
        ];

        // if ($menu = Menu::where('code', $request->module)->first()) {
        //     $menu->load('flows.group.users');
        //     $users = [];
        //     foreach ($menu->flows as $flow) {
        //         foreach ($flow->group->users as $user) {
        //             if (!in_array($user->id, $users)) {
        //                 /* Send Notification */
        //                 $user->notify(new Notify($notify));
        //                 $users[] = $user->id;
        //             }
        //         }
        //     }
        // }

        $checkFirstApprover = $this->approval($request->module)
            ->where('is_upgrade', false)
            ->whereStatus('draft')
            ->orderBy('order')
            ->whereIn('group_id', [6])
            ->first();
        if (!empty($checkFirstApprover)) {
            switch ($this->unitKerjaAsal->level) {
                case 'division':
                case 'cabang':
                    $approval = $this->firstNewApprovalGet($request->module);
                    $userCabang = User::whereHas('position', function ($q) {
                        $q->where('org_struct_id',  $this->unit_kerja_asal);
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
                        $q->where('org_struct_id',  $this->unitKerjaAsal->parent_id);
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
