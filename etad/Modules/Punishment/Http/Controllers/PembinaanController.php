<?php

namespace Modules\Punishment\Http\Controllers;

use App\Entities\EHC\Jabatan;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Vendor;
use Modules\Punishment\Entities\Pembinaan\Pembinaan;
use Yajra\DataTables\DataTables;

class PembinaanController extends Controller
{
    protected $module = 'pu.pembinaan';
    protected $route  = 'pembinaan.form';
    protected $perms  = 'pu.pembinaan';
    protected $view   = 'punishment::pembinaan.form';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'perms'  => $this->perms,
                'route'  => $this->route,
                'view'  => $this->view,
                'title'  => 'Pembinaan',
                'breadcrumb' => [
                    'Penghargaan & Pembinaan' => '/',
                    'Pembinaan' => '/',
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $user = auth()->user();
        $so_id      = $user->position->org_struct_id ?? null;
        $vendor_id      = $user->vendor_id ?? null;
        $records = Pembinaan::with('tad')
            ->when(
                $user->isEhc && !$user->cekDivisiHC() && ($so_id),
                function ($q) use ($so_id, $user) {
                    $q->whereHas(
                        'kepegawaian',
                        function ($q) use ($so_id, $user) {
                            $q->where('cabang_id', $so_id);
                            if ($user->approvalsAll) {
                                $q->orWhereHas(
                                    'approvalsAll',
                                    function ($qq) use ($user) {
                                        $qq->where(
                                            function ($qqq) use ($user) {
                                                $qqq->where('user_id', auth()->user()->id)
                                                    ->whereIn('group_id', auth()->user()->getGroupIds());
                                            }
                                        );
                                    }
                                );
                            }
                        }
                    );
                }
            )
            ->when(
                $user->isVendor && !$user->cekDivisiHC() && ($vendor_id),
                function ($q) use ($vendor_id, $user) {
                    $q->whereHas(
                        'kepegawaian',
                        function ($q) use ($vendor_id, $user) {
                            $q->where('vendor_id', $vendor_id);
                            // if ($user->approvalsAll){
                            //     $q->orWhereHas(
                            //         'approvalsAll',
                            //         function ($qq) use ($user){
                            //             $qq->where(
                            //                 function ($qqq) use ($user){
                            //                     $qqq->where('user_id', auth()->user()->id)
                            //                     ->whereIn('group_id', auth()->user()->getGroupIds());
                            //                 }
                            //             );
                            //         }
                            //     );
                            // }

                        }
                    );
                }
            )
	    ->when(
                $personil = $request->personil,
                function ($q) use ($personil) {
                    $q->where('personil', 'LIKE', '%' . $personil . '%');
                }
            )
            ->when(
                $sk = $request->sk,
                function ($q) use ($sk) {
                    $q->where('sk', 'LIKE', '%' . $sk . '%');
                }
            )
            ->when(
                $jenis_id = $request->jenis_id,
                function ($q) use ($jenis_id) {
                    $q->where('jenis_id', $jenis_id);
                }
            )
            ->when(
                $unit_kerja_id = $request->unit_kerja_id,
                function ($q) use ($unit_kerja_id) {
                    $q->whereHas(
                        'kepegawaian',
                        function ($q) use ($unit_kerja_id) {
                            // dd(61, $unit_kerja_id);
                            $q->where('cabang_id', $unit_kerja_id);
                        }
                    );
                }
            )
            ->when(
                $vendor_id = $request->vendor_id,
                function ($q) use ($vendor_id) {
                    $q->whereHas(
                        'tad.vendor',
                        function ($q) use ($vendor_id) {
                            $q->where('vendor_id', $vendor_id);
                        }
                    );
                }
            )
            ->orderBy('created_at', 'DESC');
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'sk',
                function ($r) {
                    return $r->sk . '<br>' . $r->tanggal_sk->format('d/m/Y');
                }
            )
            ->editColumn(
                'tad.nama',
                function ($r) {
                    return $r->tad->nama . '<br>' . $r->tad->vendor->nama;
                }
            )
            ->editColumn(
                'tad.kepegawaian.struct',
                function ($r) {
                    return $r->kepegawaian->unitKerja->name;
                }
            )
            ->editColumn(
                'jenis',
                function ($r) {
                    return $r->jenisPunishment->Lengkap;
                }
            )
            ->editColumn(
                'created_at',
                function ($r) {
                    return $r->createdByRaw2();
                }
            )
            ->editColumn(
                'status',
                function ($record) {
                    if ($record->status === 'submit') {
                        return '<span class="badge badge-primary text-white">Waiting Approval</span></span>';
                    } elseif ($record->status === 'approved' || $record->status === 'completed') {
                        return '<span class="badge badge-success text-white">Completed</span></span>';
                    } elseif ($record->status === 'rejected') {
                        return '<span class="badge badge-danger text-white">Ditolak</span></span>';
                    } elseif ($record->status === 'draft') {
                        return '<span class="badge badge-warning text-white">Draft</span></span>';
                    }
                }
            )
            ->editColumn(
                'action',
                function ($r) use ($user) {
                    $buttons = [];
                    $buttons[] = 'type:show|attrs:data-modal-size="modal-xl"|label:Detail|page:true|id:' . $r->id;
                    if ($user->checkPerms($this->perms . '.edit') && in_array($r->status, [0, 'draft', 'rejected'])) {
                        $buttons[] = 'type:edit|page:true|id:' . $r->id;
                    }
                    if ($user->checkPerms($this->perms . '.approve') && in_array($r->status, ['1', 'submit']) && $r->checkApproval()) {
                        $buttons[] = [
                            'type' => 'show',
                            'page' => true,
                            'id' => $r->id,
                            'label' => 'Approval',
                            'icon' => 'fa fa-check text-primary',
                            'url' => route($this->route . '.show', $r->id),
                        ];
                    }
                    if ($user->checkPerms($this->perms . '.view') && !in_array($r->status, [0, 'draft', 'new'])) {
                        $buttons[] = [
                            'type' => 'show',
                            'icon' => 'fas fa-chart-line text-info',
                            'label' => 'Tracking Approval',
                            'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                            'url' => route($this->route . '.tracking', $r->id),
                            'id' => $r->id,
                        ];
                    }
                    if ($r->status === 'approved' || $r->status === 'submit' || $r->status === 'completed') {
                        $buttons[] = 'type:print|page:true|id:' . $r->id;
                    }
                    if ($user->checkPerms($this->perms . '.delete') && in_array($r->status, [0, 'draft', 'rejected'])) {
                        $buttons[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Yakin Menghapus Data Pembinaan TAD ?"';
                    }
                    $buttons[] = [
                        'type' => 'show',
                        'label' => 'Riwayat',
                        'icon' => 'fa flaticon2-time text-success',
                        'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                        'url' => route($this->route . '.riwayat', $r->id),
                        'id' => $r->id,
                    ];
                    return $this->makeDropdown($buttons);
                }
            )
            ->rawColumns(['tad.nama', 'sk', 'status', 'created_at', 'action'])
            ->make(true);
    }

    public function index()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms . '.view',
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:sk|label:SK & Tanggal|sortable:false|className:text-center'),
                        $this->makeColumn('name:tad.nama|label:TAD|sortable:false|className:text-center'),
                        $this->makeColumn('name:tad.kepegawaian.struct|label:Unit Kerja|sortable:false|className:text-center'),
                        // $this->makeColumn('name:eviden|label:Uraian|sortable:false|className:text-left'),
                        $this->makeColumn('name:jenis|label:Jenis Pembinaan|sortable:false|className:text-center'),
                        $this->makeColumn('name:status|label:Status|sortable:false|className:text-center'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|className:text-left|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );
        $struct = OrgStruct::groupByLevel();
        $VENDOR = Vendor::with(
            [
                'categories',
                'tad' => function ($q) {
                    $q
                        ->with(
                            [
                                'jabatan'
                            ]
                        )
                        ->whereHas(
                            'kepegawaian',
                            function ($q) {
                                $q
                                    ->whereNull('resign_at')
                                    ->whereNull('out_at');
                            }
                        );
                }
            ]
        )
            ->orderBy('nama', 'ASC')
            ->get();
        return $this->render(
            $this->view . '.index',
            compact('struct', 'VENDOR')
        );
    }

    public function create()
    {
        $user = auth()->user();
        $so_id = $user->position->org_struct_id ?? null;
        $VENDOR = Vendor::with(
            [
                'tad' => function ($q) use ($user, $so_id) {
                    $q
                        ->with(
                            [
                                'kepegawaian' => function ($q) use ($so_id) {
                                    $q
                                        ->with('unitKerja')
                                        ->when(
                                            $so_id,
                                            function ($q) use ($so_id) {
                                                if ($so_id == env('APP_HC_ID')) {
                                                } else {
                                                    $q->where('cabang_id', $so_id);
                                                }
                                            }
                                        );
                                },
                                'jabatan'
                            ]
                        )
                        ->whereHas(
                            'kepegawaian',
                            function ($q) {
                                $q
                                    ->whereNull('resign_at')
                                    ->whereNull('out_at');
                            }
                        )
                        ->when(
                            $user->isVendor,
                            function ($q) use ($user) {
                                $q->where('vendor_id', $user->vendor_id);
                            }
                        )
                        ->orderBy('nama', 'ASC');
                }
            ]
        )
            ->whereHas(
                'tad',
                function ($q) use ($user) {
                    $q
                        ->when(
                            $user->isVendor,
                            function ($q) use ($user) {
                                $q->where('vendor_id', $user->vendor_id);
                            }
                        )
                        ->whereHas(
                            'kepegawaian',
                            function ($q) {
                                $q
                                    ->whereNull('out_at')
                                    ->orWhereNull('resign_at');
                            }
                        );
                }
            )
            ->orderBy('nama', 'ASC')
            ->get();
        return $this->render(
            $this->view . '.create',
            compact('VENDOR')
        );
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            [
                // 'no_surat'        => ['required'],
                'to'              => ['required'],
                'unit_kerja_id'   => ['required'],
                'posisi_id'       => ['required'],
                'vendor_id'       => ['required'],
                'tad_id'          => ['required'],
                'sk'              => ['required', 'unique:trans_pembinaan,sk'],
                'tanggal_sk'      => ['required', 'date_format:d/m/Y'],
                'eviden'          => ['required'],
                'jenis_id'        => ['required', 'exists:ref_ehc_jenis_reward,sandi'],
                'tanggal_mulai'   => ['required', 'date_format:d/m/Y'],
                'tanggal_selesai' => ['required', 'date_format:d/m/Y'],
            ],
            [],
            [
                'tad_id'          => 'Personil',
                'sk'              => 'SK',
                'eviden'          => 'Uraian',
                'jenis_id'        => 'Jenis Pembinaan',
            ],
        )->validate();
        return Pembinaan::createByRequest($request);
    }

    public function show($id)
    {
        $record = Pembinaan::findOrFail($id);
        return $this->render(
            $this->view . '.show',
            compact('record')
        );
    }

    public function edit($id)
    {
        $record = Pembinaan::findOrFail($id);
        $user = auth()->user();
        $so_id = $user->position->org_struct_id ?? null;
        $VENDOR = Vendor::with(
            [
                'tad' => function ($q) use ($user, $so_id) {
                    $q
                        ->with(
                            [
                                'kepegawaian' => function ($q) use ($so_id) {
                                    $q
                                        ->with('unitKerja')
                                        ->when(
                                            $so_id,
                                            function ($q) use ($so_id) {
                                                if ($so_id == env('APP_HC_ID')) {
                                                } else {
                                                    $q->where('cabang_id', $so_id);
                                                }
                                            }
                                        );
                                },
                                'jabatan'
                            ]
                        )
                        ->whereHas(
                            'kepegawaian',
                            function ($q) {
                                $q
                                    ->whereNull('out_at')
                                    ->orWhereNull('resign_at');
                            }
                        )
                        ->when(
                            $user->isVendor,
                            function ($q) use ($user) {
                                $q->where('vendor_id', $user->vendor_id);
                            }
                        )
                        ->orderBy('nama', 'ASC');
                }
            ]
        )
            ->whereHas(
                'tad',
                function ($q) use ($user) {
                    $q
                        ->when(
                            $user->isVendor,
                            function ($q) use ($user) {
                                $q->where('vendor_id', $user->vendor_id);
                            }
                        )
                        ->whereHas(
                            'kepegawaian',
                            function ($q) {
                                $q
                                    ->whereNull('out_at')
                                    ->orWhereNull('resign_at');
                            }
                        );
                }
            )
            ->orderBy('nama', 'ASC')
            ->get();
        return $this->render(
            $this->view . '.edit',
            compact('VENDOR', 'record')
        );
    }

    public function update(Request $request, $id)
    {
        Validator::make(
            $request->all(),
            [
                'unit_kerja_id'   => ['required'],
                'posisi_id'       => ['required'],
                'vendor_id'       => ['required'],
                'tad_id'          => ['required'],
                'sk'              => ['required', 'unique:trans_pembinaan,sk,' . $id],
                'tanggal_sk'      => ['required', 'date_format:d/m/Y'],
                'eviden'          => ['required'],
                'jenis_id'        => ['required', 'exists:ref_ehc_jenis_reward,sandi'],
                'tanggal_mulai'   => ['required', 'date_format:d/m/Y'],
                'tanggal_selesai' => ['required', 'date_format:d/m/Y'],
            ],
            [],
            [
                'tad_id'          => 'Personil',
                'sk'              => 'SK',
                'eviden'          => 'Uraian',
                'jenis_id'        => 'Jenis Pembinaan',
            ],
        )->validate();
        return Pembinaan::findOrFail($id)->updateByRequest($request);
    }

    public function destroy($id, Request $request)
    {
        return Pembinaan::find($id)->deleteByRequest($request);
    }

    public function riwayat($id)
    {
        $record = Pembinaan::with(
            [
                'logs' => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ]
        )->find($id);
        $this->setPrepare(
            [
                'title'  => 'Pembinaan TAD | <span class="label label-xl label-danger label-inline text-nowrap">' . ($record->tad->nama) . ' | SK ' . $record->sk . '</span>',
            ]
        );
        $show_title = true;
        return $this->render(
            $this->view . '.riwayat',
            compact('record', 'show_title')
        );
    }
    public function approvalSave(Request $request, $id)
    {
        if ($request->is_submit != 'approved') {
            $request->validate(
                [
                    'keterangan' => 'required|string|max:65000'
                ],
                [],
                [
                    'keterangan' => 'Keterangan',
                ]
            );
        }
        $record = Pembinaan::findOrFail($id);
        return $record->handleApproveSave($request);
    }

    public function tracking($id)
    {
        $record = Pembinaan::where('id', $id)->first();
        $this->setPrepare(
            [
                'title'  => 'Pembinaan TAD | <span class="label label-xl label-danger label-inline text-nowrap">' . ($record->tad->nama) . ' | SK ' . $record->sk . '</span>',
            ]
        );
        $show_title = true;
        return $this->render(
            $this->view . '.tracking',
            compact('show_title', 'record')
        );
    }

    public function print($id)
    {
        $record = Pembinaan::with(
            [
                'logs' => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ]
        )->find($id);
        $render = $this->render(
            $this->view . '.print',
            [
                'record' => $record,
            ]
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($render)
            ->setPaper('a4')
            // ->setOptions(['isHtml5ParserEnabled' => true])
            ->setWarnings(false);
        return $pdf->stream('Pembinaan TAD.pdf', 'Pembinaan TAD');
    }
}
