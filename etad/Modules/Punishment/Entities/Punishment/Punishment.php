<?php

namespace Modules\Punishment\Entities\Punishment;

use App\Entities\BaseModel;
use App\Entities\EHC\JenisPunishment;
use App\Entities\Traits\Approval;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Notifications\Notify;
use Modules\Settings\Entities\Menu;

class Punishment extends BaseModel
{
    use Approval;

    protected $table = 'trans_punishment';
    protected $fillable = [
        'status',
        'tad_id',
        'sk',
        'tanggal_sk',
        'eviden',
        'jenis_id',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $dates = [
        'tanggal_sk',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    protected $module = 'reward & punishment.punishment';

    public function setTanggalSkAttribute($value)
    {
        $this->attributes['tanggal_sk'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') ;
    }
    public function setTanggalMulaiAttribute($value)
    {
        $this->attributes['tanggal_mulai'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') ;
    }
    public function setTanggalSelesaiAttribute($value)
    {
        $this->attributes['tanggal_selesai'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') ;
    }

    public function logs()
    {
        return $this->hasMany(Logs::class, 'punishment_id');
    }
    public function tad()
    {
        return $this->belongsTo(Tad::class, 'tad_id');
    }

    public function jenisPunishment()
    {
        return $this->belongsTo(JenisPunishment::class, 'jenis_id', 'sandi');
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $record = new Self;
            $record->fill($request->all());
            $record->status             = $request->is_submit == 1 ? 'submit' : 'draft';
            $record->save();

            if ($request->is_submit == 1) {
                $record->generateApproval('pu.punishment');
                /* Save Logs */
                $record->createLogs('approval.waiting', 'Waiting Approval');
                $record->createLogs('logs', 'Submit Punishment', 0);

                $notify = [
                    'id' => $record->id,
                    'type' => 'pu.punishment',
                    'title' => 'Punishment TAD ' . $record->tad->nama . ' | SK '.$record->sk,
                    'from' => auth()->id(),
                    'link' => route('punishment.form.show', $record->id),
                    'message' => 'Punishment TAD membutuhkan persetujuan',
                    'content' => $record
                ];
                if ($menu = Menu::where('code', 'pu.punishment')->first()) {
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
            $this->fill($request->all());
            $this->status   = $request->is_submit == 1 ? 'submit' : 'draft';
            $this->save();

            if ($request->is_submit == 1) {
                $this->generateApproval('pu.punishment');
                /* Save Logs */
                $this->createLogs('approval.waiting', 'Waiting Approval');
                $this->createLogs('logs', 'Submit Punishment', 0);

                $notify = [
                    'id' => $this->id,
                    'type' => 'pu.punishment',
                    'title' => 'Punishment TAD ' . $this->tad->nama . ' | SK '.$this->sk,
                    'from' => auth()->id(),
                    'link' => route('punishment.form.show', $this->id),
                    'message' => 'Punishment TAD membutuhkan persetujuan',
                    'content' => $this
                ];
                if ($menu = Menu::where('code', 'pu.punishment')->first()) {
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
                $this->createLogs('draft', 'Data Tersimpan Sebagai Draft');
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

    public function deleteByRequest()
    {
        \DB::beginTransaction();
        try {
            $this->logs()->delete();
            $this->delete();
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
                'type' => 'pu.punishment',
                'title' => 'Punishment TAD ' . $this->tad->nama . ' | SK '.$this->sk,
                'from' => auth()->id(),
                'link' => route('punishment.form.show', $this->id),
                'content' => $this
            ];

            if ($is_approved) {
                $this->createLogs('logs', 'Menyetujui Punishment TAD', 0);
                // Check Approval
                if ($this->approvals()->whereIn('status', ['draft', 'rejected'])->count() == 0) {
                    $this->update(['status' => 'completed']);
                    $notify['message'] = 'Proses Persetujuan telah selesai.';
                    $this->creator->notify(new Notify($notify));
                }
            } else {
                $this->createLogs('logs', 'Punishment Ditolak, Keterangan: ' . $request->keterangan, 0);
                $this->update(['status' => 'rejected']);
                $notify['message'] = 'Ditolak oleh ' . $approval_user->name;
                $this->creator->notify(new Notify($notify));
            }

            $new_data = json_encode($this->load('approvals'));
            auth()->user()->storeLog('reward & punishment.punishment', 'approval', $new_data, $old_data);
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
