<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use App\Entities\EHC\Cabang;
use App\Entities\Traits\Approval;
use App\Entities\Traits\HasFiles;
use App\Entities\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\SO\Positions;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Notifications\Notify;
use Modules\Settings\Entities\Menu;

class Pengajuan extends BaseModel
{
    use HasFactory, Approval, HasFiles;

    protected $table = 'trans_pengajuan_tad';
    protected $fillable = [
        'position_id',
        'status',
        'so_id',
        'no_tiket',
        'no',
        'perihal',
        'tgl_pengajuan',
        'cabang_id',
        'so_filename',
        'so_filepath',
        'surat_filename',
        'surat_filepath',
        'year',
        'semester',
        'pembukaan',
        'penutupan',
    ];

    protected $dates = ['verified_at', 'tgl_pengajuan'];

    public function setVerifiedAtAttribute($value)
    {
        $this->attributes['verified_at'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }
    public function setTglPengajuanAttribute($value)
    {
        // dd(54, $value);
        $this->attributes['tgl_pengajuan'] = $value ? Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d') : null;
    }

    // Dipakai di Dashboard oleh User Vendor
    public function scopeOtorisasiVendor()
    {
        return $this->where(
            function ($q) {
                $q->whereHas(
                    'logs',
                    function ($w) {
                        $w->where(
                            function ($wh) {
                                $wh->where(
                                    [
                                        'is_active' => 1,
                                        'status' => 'waiting.vendor'
                                    ]
                                );
                            }
                        )
                            ->orWhere(
                                function ($ow) {
                                    $ow->where(['is_active' => 1, 'status' => 'rewaiting.vendor']);
                                }
                            );
                    }
                )
                    ->whereHas(
                        'requirement',
                        function ($rq) {
                            // $rq->where('jumlah','<',$rq->whereHas('kandidats',function($ks){
                            //     $ks->where('accepted', 1);
                            // })->count());
                            $rq->where('vendor_id', auth()->user()->vendor_id);
                            $rq->where(
                                function ($w) {
                                    $w->where('flag', 'process');
                                    $w->orWhere('flag', 'open');
                                }
                            );
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
            }
        );
    }

    public function scopeMonitoring($query)
    {
        if (auth()->user()->isVendor) {
            return $query->whereHas(
                'requirement',
                function ($r) {
                    $r->where('vendor_id', auth()->user()->vendor_id);
                }
            );
        }
        return $query;
    }

    public function requirementByVendor()
    {
        return $this->requirement()
            ->where(
                [
                    'vendor_id' => auth()->user()->vendor_id
                ]
            )->get();
    }

    public function submitted()
    {
        if ($this->requirementByVendor()->where('flag', 'process')->count() == count($this->requirementByVendor())) {
            return false;
        }
        return true;
    }

    public function cc()
    {
        return $this->belongsToMany(User::class, CC::class, 'pengajuan_id', 'user_id');
        // return $this->belongsToMany(Positions::class, CC::class, 'pengajuan_id', 'user_id');
    }

    public function to()
    {
        return $this->belongsToMany(Positions::class, 'trans_pengajuan_tad_to', 'trans_id', 'user_id');
    }

    public function ccHasMany()
    {
        return $this->hasMany(CC::class, 'rkia_id');
    }

    public function pivotTad()
    {
        return $this->belongsToMany(Tad::class, 'trans_pengajuan_tad_pivot', 'pengajuan_id', 'tad_id');
    }

    public function requirement()
    {
        return $this->hasMany(Requirement::class, 'pengajuan_id');
    }

    public function kandidat()
    {
        return $this->hasManyThrough(Kandidat::class, Requirement::class);
    }

    public function logs()
    {
        return $this->hasMany(Logs::class, 'pengajuan_id');
    }

    public function active_logs()
    {
        return $this->hasOne(Logs::class, 'pengajuan_id')
            ->where('is_active', 1);
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'KDCAB');
    }

    public function position()
    {
        return $this->belongsTo(Positions::class, 'position_id');
    }
    public function so()
    {
        return $this->belongsTo(OrgStruct::class, 'so_id');
    }

    public function sp()
    {
        return $this->hasMany(TL::class, 'pengajuan_id');
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
        return 'TAD-' . Carbon::now()->format('dmy') . str_pad($lastNumber + 1, 3, 0, STR_PAD_LEFT);
    }

    public function show_tgl_pengajuan()
    {
        return Carbon::createFromFormat('Y-m-d', $this->tgl_pengajuan);
    }

    public function getFileIconAttribute($value)
    {
        $icon = 'far fa-file-alt';
        $type =  preg_replace('/^.*\.([^.]+)$/D', '$1', $value);
        if ($type == 'pdf') {
            $icon = 'text-danger far fa-file-pdf';
        } else if ($type == 'xlsx') {
            $icon = 'text-success far fa-file-excel';
        } else if ($type == 'jpg' || $type == 'png') {
            $icon = 'text-warning far fa-file-image';
        } else if ($type == 'ppt') {
            $icon = 'text-danger far fa-file-powerpoint';
        } else if ($type == 'docx') {
            $icon = 'text-primary far fa-file-word';
        }
        return $icon . ' ' . $type;
    }

    public static function createByRequest($request)
    {
        \DB::beginTransaction();
        $user = auth()->user()->load('position.struct');
        Self::validateStore($request);
        try {
            $record = new Self;
            $record->fill(
                [
                    'no_tiket'      => (new Self)->generateTicketNumber(),
                    'cabang_id'     => $user->ehc->cabang->KDCAB ?? null,
                    'position_id'   => null,
                    'so_id'         => $request->so_id ?? null,
                    'tgl_pengajuan' => $request->tgl_pengajuan,
                    'year'          => $request->year,
                    'semester'      => $request->semester,
                    'no'            => $request->no,
                    'perihal'       => $request->perihal,
                    'pembukaan'     => $request->pembukaan,
                    'penutupan'     => $request->penutupan,
                ]
            );
            $record->status = $request->is_submit == 1 ? 'waiting.approval' : 'draft';

            $record->save();
            $record->saveFilesByTemp($request->uploads_sp, $request->module, 'surat_permohonan');
            $record->saveFilesByTemp($request->uploads_so, $request->module, 'so');
            $record->cc()->sync($request->cc ?? []);
            $record->to()->sync($request->to ?? []);

            /* Save Requirement */
            $requirement = [];
            // dd(242, $request->jabatan_id);
            foreach ($request->jabatan_id as $key => $item) {
                $requirement[] = new Requirement(
                    [
                        'jabatan_id' => $request->jabatan_id[$key],
                        'jenis_kelamin' => $request->jenis_kelamin[$key],
                        'vendor_id' => $request->vendor_id[$key],
                        'jumlah' => $request->jumlah[$key],
                    ]
                );
            }
            $record->requirement()->saveMany($requirement);

            if ($request->is_submit == 1) {
                $tipe = '';
                switch ($record->so->level) {
                    case 'bod':
                        $tipe = 'tad-bod';
                        $record->generateApproval('tad-bod');
                        break;
                    case 'vice':
                        $tipe = 'tad-vice';
                        $record->generateApproval('tad-vice');
                        break;
                    case 'division':
                        $tipe = 'tad-division';
                        $record->generateApproval('tad-division');
                        break;
                    case 'departemen':
                        $tipe = 'tad-departemen';
                        $record->generateApproval('tad-departemen');
                        break;
                    case 'cabang':
                        $tipe = 'tad-cabang';
                        $record->generateApproval('tad-cabang');
                        break;
                    case 'capem':
                        $tipe = 'tad-capem';
                        $record->generateApproval('tad-capem');
                        break;
                    case 'kas':
                        $tipe = 'tad-kas';
                        $record->generateApproval('tad-kas');
                        break;
                }
                // $record->generateApproval($request->module);
                $record->saveLogNotifyApproval($request);

                /* Save Logs */
                $record->createLogs('logs', 'Submit pengajuan', 0);
                auth()->user()->storeLog($request->module, 'create');
                auth()->user()->storeLog($request->module, 'submit');

                $notify = [
                    'id' => $record->id,
                    'type' => $request->module,
                    'title' => 'Pengajuan TAD : ' . $record->no_tiket,
                    'from' => auth()->id(),
                    'link' => route('pengajuan.pengajuan.show', $record->id),
                    'message' => 'Pengajuan TAD membutuhkan persetujuan',
                    'content' => $record
                ];
                // CC
                $notify['message'] = auth()->user()->name . ' membuat Pengajuan TAD.';
                foreach (($record->cc ?? []) as $cc) {
                    // foreach ($cc->users as $user) {
                    //     $user->notify(new Notify($notify));
                    // }
                    // User
                    $cc->notify(new Notify($notify));
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
        $this->validateUpdate($request);
        try {
            $data = $request->all();
            $data['tgl_pengajuan'] = $request->tgl_pengajuan;
            $data['status'] = $request->is_submit == 1 ? 'waiting.approval' : 'draft';
            $old = $this->replicate();

            $this->update($data);
            $this->saveFilesByTemp($request->uploads_sp, $request->module, 'surat_permohonan');
            $this->saveFilesByTemp($request->uploads_so, $request->module, 'so');
            $this->cc()->sync($request->cc ?? []);
            $this->to()->sync($request->to ?? []);

            $fill = [];
            foreach ($request->requirement as $key => $item) {
                $fill[] = ($p = $this->requirement->find($key)) ? $p->fill($item) : new Requirement($item);
            }
            $this->requirement()->saveMany($fill);

            // if ($this->active_logs->status == 'rejected.hc') {
            //     $this->disableStatusBefore();
            //     $this->createLogs('logs', 'Submit pengajuan', 0);
            // }

            if ($request->is_submit == 1) {
                // $tipe = '';
                switch ($this->so->level) {
                    case 'bod':
                        $tipe = 'tad-bod';
                        $this->generateApproval('tad-bod');
                        break;
                    case 'vice':
                        $tipe = 'tad-vice';
                        $this->generateApproval('tad-vice');
                        break;
                    case 'division':
                        $tipe = 'tad-division';
                        $this->generateApproval('tad-division');
                        break;
                    case 'departemen':
                        $tipe = 'tad-departemen';
                        $this->generateApproval('tad-departemen');
                        break;
                    case 'cabang':
                        $tipe = 'tad-cabang';
                        $this->generateApproval('tad-cabang');
                        break;
                    case 'capem':
                        $tipe = 'tad-capem';
                        $this->generateApproval('tad-capem');
                        break;
                    case 'kas':
                        $tipe = 'tad-kas';
                        $this->generateApproval('tad-kas');
                        break;
                }
                $this->saveLogNotifyApproval($request);

                /* Save Logs */
                $this->createLogs('logs', 'Submit pengajuan', 0);
                auth()->user()->storeLog($request->module, 'update');
                auth()->user()->storeLog($request->module, 'submit');
                $notify = [
                    'id' => $this->id,
                    'type' => $request->module,
                    'title' => 'Pengajuan TAD : ' . $this->no_tiket,
                    'from' => auth()->id(),
                    'link' => route('pengajuan.pengajuan.show', $this->id),
                    'message' => 'Pengajuan TAD membutuhkan persetujuan',
                    'content' => $this
                ];

                // CC
                $notify['message'] = auth()->user()->name . ' membuat Pengajuan TAD.';
                foreach ($this->cc as $cc) {
                    // Jabatan
                    // foreach ($cc->users as $user) {
                    //     $user->notify(new Notify($notify));
                    // }
                    // User
                    $cc->notify(new Notify($notify));
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
            Storage::delete([$this->so_filepath, $this->surat_filepath]);
            auth()->user()->storeLog($request->module, 'delete');
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

    public static function validateStore($request)
    {
        $user = auth()->user();
        $jumlah_available = [];
        $jumlah_rules = [];
        $requirement_payload = [
            'kategori_id'   => $request->kategori_id,
            'jabatan_id'    => $request->jabatan_id,
            'jenis_kelamin' => $request->jenis_kelamin,
            'jumlah'        => $request->jumlah,
            'vendor_id'     => $request->vendor_id,
        ];
        $requirement_map = [];
        $now = now();
        foreach ($request->jabatan_id as $key => $item) {
            if (!isset($requirement_map[$item])) {
                $requirement_map[$item] = 0;
            }
            $requirement_map[$item] += $request->jumlah[$key] ?? 0;
        }
        foreach ($requirement_map as $jabatan_id => $jumlah) {
            foreach ($request->jabatan_id as $key => $item) {
                if ($jabatan_id == $item) {
                    $requirement_payload['jumlah'][$key] = $jumlah;
                }
            }
        }
        foreach ($requirement_payload['jabatan_id'] as $key => $item) {
            $available = Quota::countAvailable(
                $request->year,
                $request->semester,
                $request->level,
                $request->so_id,
                $request->jabatan_id[$key]
            );
            $jumlah_available[$key] = $available;
            $jumlah_rules['jumlah.' . $key]     = ['required', 'numeric', 'gt:0', 'max:' . $available];
        }
        $validator = Validator::make(
            $requirement_payload,
            $jumlah_rules,
            [
                'max' => 'Melebihi Batas Kuota'
            ]
        );
        return $validator->validate();
    }

    public function validateUpdate($request)
    {
        $jumlah_available = [];
        $jumlah_rules = [];
        $requirement_payload = $request->requirement;
        $requirement_map = [];
        foreach ($request->requirement as $key => $requirement) {
            if (!isset($requirement_map[$requirement['jabatan_id']])) {
                $requirement_map[$requirement['jabatan_id']] = 0;
            }
            $requirement_map[$requirement['jabatan_id']] += $requirement['jumlah'] ?? 0;
        }
        foreach ($requirement_map as $jabatan_id => $jumlah) {
            foreach ($requirement_payload as $key => $requirement) {
                if ($jabatan_id == $requirement['jabatan_id']) {
                    $requirement_payload[$key]['jumlah'] = $jumlah;
                }
            }
        }
        foreach ($requirement_payload as $key => $requirement) {
            $available = Quota::countAvailable(
                $request->year,
                $request->semester,
                $request->level,
                $request->so_id,
                $requirement['jabatan_id']
            );
            $jumlah_available[$requirement['jabatan_id']] = $available;
            if (!isset($jumlah_rules[$requirement['jabatan_id']])) {
                $jumlah_rules[$requirement['jabatan_id']] = [];
            }
            $jumlah_rules[$requirement['jabatan_id']][$key]     = [
                'required',
                'numeric',
                'gt:0',
                'max:' . $available
            ];
        }
        foreach ($this->requirement as $key => $requirement) {
            foreach ($jumlah_available as $jabatan_id => $jumlah) {
                if ($requirement->jabatan_id == $jabatan_id) {
                    $jumlah_available[$jabatan_id] = $jumlah_available[$jabatan_id];
                }
            }
        }
        $new_jumlah_rules = [];
        foreach ($jumlah_rules as $jabatan_id_o => $requirement_by_id) {
            foreach ($requirement_by_id as $requirement_id => $rules) {
                // dd(586, $jabatan_id_o, $requirement_by_id, $requirement_id, $rules, $jumlah_available);
                foreach ($jumlah_available as $jabatan_id_i => $jumlah) {
                    if ($jabatan_id_i == $jabatan_id_o) {
                        $new_jumlah_rules['requirement.' . $requirement_id . '.jumlah']     = [
                            'required',
                            'numeric',
                            'gt:0',
                            function ($attribute, $value, $fail) use ($jumlah) {
                            	if ($value > $jumlah) {
                            		$fail('Jumlah melebihi sisa kuota yang tersedia.');
                        	}
                    	    },
                        ];
                    }
                }
            }
        }
        // dd(573, $jumlah_available, $requirement_payload, $jumlah_rules, $new_jumlah_rules, json_decode($this->requirement));
        $validator = Validator::make(
            ['requirement' => $requirement_payload],
            $new_jumlah_rules,
            [
                'max' => 'Melebihi Batas Kuota'
            ]
        );
        return $validator->validate();
    }

    // Vendor Submit Kandidat Ke HC
    public function vendorByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $requirement = $this->requirement()->where('vendor_id', auth()->user()->vendor_id)->get();
            $this->module = null;
            foreach ($requirement as $item) {
                if ($item->kandidats->count() < $item->jumlah) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Kandidat belum terpenuhi',
                            'trace' => null,
                        ],
                        422
                    );
                }
                if ($item->flag != 'process') {
                    $item->update(['flag' => 'process']);
                }
            }
            $this->createLogs('submitted.vendor', 'Vendor ' . auth()->user()->vendor->nama . ' telah mensubmit kandidat', 0);
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

    public function spByRequest($request)
    {
        \DB::beginTransaction();
        try {
            /* Validasi Tindak Lanjut */
            $kandidat = $this->kandidat();
            if ($this->kandidat()->whereNotNull('accepted')->count() < $this->kandidat()->count()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Beberapa Kandidat belum di tindak lanjuti',
                        'trace' => null,
                    ],
                    422
                );
            }

            // $batch = $kandidat->whereNull('batch')->update();

            $surat = $request->file('surat');
            $suratDir = 'pengajuan-tad/surat-tl/';
            $suratFile = \Str::uuid() . '.' . $surat->getClientOriginalExtension();
            Storage::putFileAs($suratDir, $surat, $suratFile);

            $batch = $this->sp()->count() + 1;
            $sp = $this->sp()->save(
                new TL(
                    [
                        'no_surat' => $request->no_surat,
                        'surat_filepath' => $suratDir . $suratFile,
                        'surat_filename' => $surat->getClientOriginalName(),
                        'batch' => $batch
                    ]
                )
            );

            Kandidat::where(
                function ($w) {
                    $w->whereNull('batch')
                        ->whereIn('requirement_id', $this->requirement()->pluck('id'));
                }
            )->update(
                [
                    'batch' => $batch,
                    'tl_id' => $sp->id
                ]
            );

            if ($kandidat->where('accepted', 1)->count() < $this->requirement()->sum(\DB::raw('CAST(jumlah as float)'))) {
                $this->disableStatusBefore();
                $this->createLogs('rewaiting.vendor', 'Beberapa Kandidat belum memenuhi kriteria Bank Jatim sehingga Vendor perlu menyiapkan kembali kandidat');
            } elseif ($kandidat->where('accepted', 1)->count() == 0) {
                $this->disableStatusBefore();
                $this->createLogs('reprocess.vendor', 'Kandidat belum memenuhi kriteria Bank Jatim sehingga Vendor perlu menyiapkan kembali kandidat');
            } else {
                $this->disableStatusBefore();
                $this->createLogs('closed', 'Pengajuan Closed, Kandidat sudah memenuhi kebutuhan');
            }

            /* Notification to Cabang / Divisi */
            $notify = [
                'id' => $this->id,
                'type' => 'pengajuan.tad.form',
                'title' => 'Pengajuan TAD : ' . $this->no_tiket,
                'from' => auth()->id(),
                'link' => route('pengajuan.pengajuan.show', $this->id),
                'message' => $this->active_logs->keterangan,
                'content' => $this
            ];
            // dd($notify);
            $this->creator->notify(new Notify($notify));

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

    public function spUpdateByRequest($request)
    {
        \DB::beginTransaction();
        try {
            $surat = $request->file('surat');
            $suratFiles = [];
            if (!empty($surat)) {
                $suratDir = 'pengajuan-tad/surat-tl/';
                $suratFile = \Str::uuid() . '.' . $surat->getClientOriginalExtension();
                Storage::putFileAs($suratDir, $surat, $suratFile);
                $suratFiles = [
                    'surat_filepath' => $suratDir . $suratFile,
                    'surat_filename' => $surat->getClientOriginalName()
                ];
            }

            $this->sp()->update(
                array_merge(
                    $suratFiles,
                    [
                        'no_surat' => $request->no_surat,
                    ]
                )
            );
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
            $approval->status       = $is_approved ? 'approved' : 'rejected';
            $approval->keterangan   = $is_approved ? null : $request->keterangan;
            $approval->approved_at  = $is_approved ? now() : null;
            $approval->position_id  = $approval_user->position_id;
            $approval->user_id      = $approval_user->id;
            $approval->save();

            $notify = [
                'id' => $this->id,
                'type' => $request->module,
                'title' => in_array($this->status, ['completed']) ? 'Revisi ' : '' . 'Pengajuan TAD : ' . $this->no_tiket,
                'from' => $approval_user->id,
                'link' => '',
                'message' => '',
                'content' => $this
            ];
            if ($is_approved) {
                $this->createLogs('logs', 'Menyetujui Pengajuan TAD', 0);
                // Check Approval
                if ($this->approvals()->whereIn('status', ['draft', 'rejected'])->count() == 0) {
                    if ($this->status == 'waiting.approval.upgrade') {
                        $this->update([
                            'version'   => $this->version + 1,
                            'status'    => 'draft',
                        ]);
                    } else {
                        $this->update(['status' => 'completed']);

                        $user_vendor_ids = [];
                        $this->load('requirement.vendor.user');
                        foreach ($this->requirement as $requirement) {
                            foreach ($requirement->vendor->user as $user_vendor) {
                                if (in_array($user_vendor->id, $user_vendor_ids)) {
                                } else {
                                    $user_vendor_ids[] = $user_vendor->id;
                                    $notify['link'] = route('pengajuan.kandidat.index');
                                    $notify['message'] = 'Pengajuan TAD memerlukan kandidat TAD';
                                    $user_vendor->notify(new Notify($notify));
                                }
                            }
                        }
                    }
                }
                $this->saveLogNotifyApproval($request);
                auth()->user()->storeLog($request->module, 'approval');
            } else {
                if (!in_array($this->status, ['waiting.approval', 'submit'])) {
                    auth()->user()->storeLog($request->module, 'reject');
                    $this->createLogs('logs', 'Revisi Pengajuan Ditolak, Keterangan: ' . $request->keterangan, 0);
                    $this->update(['status' => 'completed']);
                    auth()->user()->storeLog($request->module, 'reject');
                } else {
                    $this->createLogs('logs', 'Pengajuan Ditolak, Keterangan: ' . $request->keterangan, 0);
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
            'title' => in_array($this->status, ['waiting.approval.upgrade', 'waiting.approval.revisi', 'draft']) ? 'Revisi ' : '' . 'Pengajuan TAD : ' . $this->no_tiket,
            'from' => auth()->id(),
            'link' => route('pengajuan.pengajuan.show', $this->id),
            'message' => 'Pengajuan TAD membutuhkan persetujuan',
            'content' => $this
        ];
        // $tipe = '';
        // switch ($this->so->level) {
        //     case 'bod':
        //         $tipe = 'tad-bod';
        //         break;
        //     case 'vice':
        //         $tipe = 'tad-vice';
        //         break;
        //     case 'division':
        //         $tipe = 'tad-division';
        //         break;
        //     case 'departemen':
        //         $tipe = 'tad-departemen';
        //         break;
        //     case 'cabang':
        //         $tipe = 'tad-cabang';
        //         break;
        //     case 'capem':
        //         $tipe = 'tad-capem';
        //         break;
        //     case 'kas':
        //         $tipe = 'tad-kas';
        //         break;
        // }
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
                        $q->where('org_struct_id',  $this->so_id);
                    })->pluck('id')->toArray();
                    $users = $userCabang;
                    if ($approval != null) {
                        foreach ($approval->group->users as $qq) {
                            if (in_array($qq->id, $users)) {
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
                    if ($approval != null) {
                        foreach ($approval->group->users as $qq) {
                            if (in_array($qq->id, $users)) {
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

    public function getTanggalAttribute()
    {
        $d = $this->created_at->format("d");
        $F = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'Septer',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ][$this->created_at->format("m")];
        $Y = $this->created_at->format("Y");
        return "{$d} {$F} {$Y}";
    }
}
