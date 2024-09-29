<?php

namespace Modules\Punishment\Entities\Pembinaan;

use App\Entities\BaseModel;
use App\Entities\EHC\JenisPunishment;
use App\Entities\SysFile;
use App\Entities\Traits\Approval;
use App\Entities\Traits\HasFiles;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Modules\Master\Entities\SO\Positions;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Notifications\Notify;
use Modules\Settings\Entities\Menu;

class Pembinaan extends BaseModel
{
    use Approval, HasFiles;

    protected $table = 'trans_pembinaan';
    protected $fillable = [
        'status',
        'tad_id',
        'kepegawaian_id',
        'sk',
        'tanggal_sk',
        'eviden',
        'jenis_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'to',
        'no_surat',
	'personil'
    ];

    protected $dates = [
        'tanggal_sk',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    public function setTanggalSkAttribute($value)
    {
        $this->attributes['tanggal_sk'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }
    public function setTanggalMulaiAttribute($value)
    {
        $this->attributes['tanggal_mulai'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }
    public function setTanggalSelesaiAttribute($value)
    {
        $this->attributes['tanggal_selesai'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }

    public function logs()
    {
        return $this->hasMany(Logs::class, 'pembinaan_id');
    }
    public function tad()
    {
        return $this->belongsTo(Tad::class, 'tad_id');
    }
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to');
    }

    public function kepegawaian()
    {
        return $this->belongsTo(Kepegawaian::class, 'kepegawaian_id');
    }

    public function jenisPunishment()
    {
        return $this->belongsTo(JenisPunishment::class, 'jenis_id', 'sandi');
    }
    public function lampiran()
    {
        return $this->hasMany(SysFile::class, 'target_id')
            ->where('target_type', Self::class);
    }

    public function cc()
    {
        return $this->belongsToMany(Positions::class, CC::class, 'pembinaan_id', 'user_id');
    }

    public function ccHasMany()
    {
        return $this->hasMany(CC::class, 'pembinaan_id');
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

    public static function validateTanggal($request)
    {
        $tanggal_sk         = Carbon::createFromFormat('d/m/Y', $request->tanggal_sk)->format('Ymd');
        $tanggal_mulai      = Carbon::createFromFormat('d/m/Y', $request->tanggal_mulai)->format('Ymd');
        $tanggal_selesai    = Carbon::createFromFormat('d/m/Y', $request->tanggal_selesai)->format('Ymd');
        Validator::make(
            [
                'tanggal_sk'        => $tanggal_sk,
                'tanggal_mulai'     => $tanggal_mulai,
                'tanggal_selesai'   => $tanggal_selesai,
            ],
            [
                'tanggal_sk'        => [],
                'tanggal_mulai'     => [
                    function ($attribute, $value, $fail) use ($tanggal_sk) {
                        if ($value >= $tanggal_sk) {
                        } else {
                            $fail('Tanggal Mulai harus >= Tanggal SK');
                        }
                    }
                ],
                // 'tanggal_selesai'   => [
                //     function ($attribute, $value, $fail) use ($tanggal_mulai) {
                //         if (strtotime($value) >= strtotime($tanggal_mulai)) {
                //         } else {
                //             $fail('Tanggal Selesai harus >= Tanggal Mulai');
                //         }
                //     }
                // ],
            ],
            []
        )
            ->validate();
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        Self::validateTanggal($request);
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

            $record = new Self;
            $record->fill($request->all());
            $record->status             = $request->is_submit == 1 ? 'submit' : 'draft';
            $record->personil = $record->tad->nama;
	    $record->save();
            $record->kepegawaian_id = $record->tad->kepegawaian_id;
            $record->update();
            $record->saveCc($request->cc ?? []);


            $record->saveFilesByTemp($request->uploads, $request->module, 'lampiran');


            if ($request->is_submit == 1) {
                $record->generateApproval($request->module);
                /* Save Logs */
                $record->createLogs('logs', 'Submit Pembinaan', 0);
                $record->createLogs('approval.waiting', 'Waiting Approval');
                auth()->user()->storeLog($request->module, 'create');
                auth()->user()->storeLog($request->module, 'submit');

                $notify = [
                    'id' => $record->id,
                    'type' => $request->module,
                    'title' => 'Pembinaan TAD ' . $record->tad->nama . ' | SK ' . $record->sk,
                    'from' => auth()->id(),
                    'link' => route('pembinaan.form.show', $record->id),
                    'message' => 'Pembinaan TAD membutuhkan persetujuan',
                    'content' => $record
                ];
                if ($menu = Menu::where('code', $request->module)->first()) {
                    $menu->load('flows.group.users');
                    $users = [];
                    foreach ($menu->flows as $flow) {
                        foreach ($flow->group->users as $user) {
                            if (!in_array($user->id, $users)) {
                                /* Send Notification */
                                $user->notify(new Notify($notify));
                                $users[] = $user->id;
                            }
                        }
                    }
                }
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
        Self::validateTanggal($request);
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

            $this->fill($request->all());
            $this->status   = $request->is_submit == 1 ? 'submit' : 'draft';
            // dd(json_decode($this), $request->all());
	    $this->personil = $this->tad->nama;
            $this->save();
            $this->kepegawaian_id = $this->tad->kepegawaian_id;
            $this->update();
            $this->saveCc($request->cc ?? []);

            $this->saveFilesByTemp($request->uploads, $request->module, 'lampiran');

            if ($request->is_submit == 1) {
                $this->generateApproval($request->module);
                /* Save Logs */
                $this->createLogs('approval.waiting', 'Waiting Approval');
                $this->createLogs('logs', 'Submit Pembinaan', 0);
                auth()->user()->storeLog($request->module, 'update');
                auth()->user()->storeLog($request->module, 'submit');

                $notify = [
                    'id' => $this->id,
                    'type' => $request->module,
                    'title' => 'Pembinaan TAD ' . $this->tad->nama . ' | SK ' . $this->sk,
                    'from' => auth()->id(),
                    'link' => route('pembinaan.form.show', $this->id),
                    'message' => 'Pembinaan TAD membutuhkan persetujuan',
                    'content' => $this
                ];
                if ($menu = Menu::where('code', $request->module)->first()) {
                    $menu->load('flows.group.users.position');
                    $users = [];
                    foreach ($menu->flows as $flow) {
                        foreach ($flow->group->users as $user) {
                            if (!in_array($user->id, $users)) {
                                /* Send Notification */
                                if ($flow->group->id == 6) {
                                    if ($user->position->org_struct_id == $this->tad->kepegawaian->cabang_id) {
                                        $user->notify(new Notify($notify));
                                        $users[] = $user->id;
                                    }
                                } else {
                                    $user->notify(new Notify($notify));
                                    $users[] = $user->id;
                                }
                            }
                        }
                    }
                }
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
                'message' => 'Data successfuly updated',
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
                'title' => 'Pembinaan TAD ' . $this->tad->nama . ' | SK ' . $this->sk,
                'from' => auth()->id(),
                'link' => route('pembinaan.form.show', $this->id),
                'content' => $this
            ];

            if ($is_approved) {
                $this->createLogs('logs', 'Menyetujui Pembinaan TAD', 0);
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
                auth()->user()->storeLog($request->module, 'approval');
            } else {
                if (!in_array($this->status, ['waiting.approval', 'submit'])) {
                    $this->createLogs('logs', 'Revisi Pembinaan Ditolak, Keterangan: ' . $request->keterangan, 0);
                    $this->update(['status' => 'completed']);
                    auth()->user()->storeLog($request->module, 'reject');
                } else {
                    $this->createLogs('logs', 'Pembinaan Ditolak, Keterangan: ' . $request->keterangan, 0);
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
}
