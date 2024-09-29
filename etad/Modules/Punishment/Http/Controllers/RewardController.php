<?php

namespace Modules\Punishment\Http\Controllers;

use App\Entities\EHC\Jabatan;
use App\Http\Controllers\Controller;
use App\Support\Base;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Vendor;
use Modules\Punishment\Entities\Reward\Reward;
use Yajra\DataTables\DataTables;

class RewardController extends Controller
{
    protected $module = 'pu.reward';
    protected $route  = 'reward.form';
    protected $perms  = 'pu.reward';
    protected $view   = 'punishment::reward.form';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'perms'  => $this->perms,
                'route'  => $this->route,
                'view'   => $this->view,
                'title'  => 'Penghargaan',
                'breadcrumb' => [
                    'Penghargaan & Pembinaan' => '/',
                    'Penghargaan' => '/',
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $user = auth()->user();
        $so_id          = $user->position->org_struct_id ?? null;
        $unit_kerja_id  = $request->unit_kerja_id ?? $so_id;
        $vendor_id      = $user->vendor_id ?? null;
        $records = Reward::with('tad', 'kepegawaian')
            ->when(
                $user->isEhc && !$user->cekDivisiHC() && ($so_id),
                function ($q) use ($so_id, $user) {
                    $q->whereHas(
                        'kepegawaian',
                        function ($q) use ($so_id, $user) {
                            $q->where('cabang_id', $so_id);
                            if ($user->approvalsAll){
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
            ->orderBy('updated_at', 'DESC');
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'tad.nama',
                function ($r) {
                    return  $r->tad->nama . '<br>' . $r->tad->vendor->nama;
                }
            )
            ->editColumn(
                'tad.kepegawaian.struct',
                function ($r) {
                    return $r->kepegawaian->unitKerja->name;
                }
            )
            ->editColumn(
                'sk',
                function ($r) {
                    return $r->sk . '<br>' . $r->tanggal_reward->format('d/m/Y');
                }
            )
            ->editColumn(
                'jenis',
                function ($r) {
                    return '<span>' . $r->jenisPunishment->Lengkap . '</span>';
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
                    if ($record->status == 'submit') {
                        return '<span class="badge badge-primary text-white">Waiting Approval</span></span>';
                    } elseif ($record->status === 'approved' || $record->status === 'completed') {
                        return '<span class="badge badge-success text-white">Completed</span></span>';
                    } elseif ($record->status == 'rejected') {
                        return '<span class="badge badge-danger text-white">Ditolak</span></span>';
                    } elseif ($record->status == 'draft') {
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
                    if ($user->checkPerms($this->perms . '.delete') && in_array($r->status, [0, 'draft', 'rejected'])) {
                        $buttons[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Yakin Menghapus Data Penghargaan TAD SK ' . $r->sk . ' ?"';
                    }

                    if (in_array($r->status, ['waiting.approval', 'approval.waiting', 'submit', 'completed', 'approved']) && auth()->user()->checkPerms($this->perms . '.view')) {
                        $buttons[] = 'type:print|label:Cetak|page:true|id:' . $r->id;
                    }

                    if (!in_array($r->status, ['draft']) && auth()->user()->checkPerms($this->perms . '.view')) {
                        $buttons[] = [
                            'type' => 'show',
                            'icon' => 'fas fa-chart-line text-info',
                            'label' => 'Tracking Approval',
                            'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                            'url' => route($this->route . '.tracking', $r->id),
                            'id' => $r->id,
                        ];
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
            ->rawColumns(
                [
                    'sk',
                    'tad.nama',
                    'tad.kepegawaian.struct',
                    'jenis',
                    'status',
                    'created_at',
                    'action'
                ]
            )
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
                        $this->makeColumn('name:jenis|label:Jenis Penghargaan|sortable:false|className:text-center'),
                        $this->makeColumn('name:status|label:Status|sortable:false|className:text-center'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|className:text-left|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );
        $struct = OrgStruct::whereHas('employes')
            ->where('level', '!=', 'root')
            ->get()
            ->groupBy('level');
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
                                    ->whereNull('out_at')
                                    ->orWhereNull('resign_at');
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

    public function show($id)
    {
        $record = Reward::findOrFail($id);
        return $this->render(
            $this->view . '.show',
            compact('record')
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
                                'kepegawaian' => function ($q) {
                                    $q->with('unitKerja');
                                },
                                'jabatan'
                            ]
                        )
                        ->whereHas(
                            'kepegawaian',
                            function ($q) use ($so_id) {
                                $q
                                    ->when(
                                        $so_id,
                                        function ($q) use ($so_id) {
                                            if ($so_id == env('APP_HC_ID')) {
                                            } else {
                                                $q->where('cabang_id', $so_id);
                                            }
                                        }
                                    )
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
            $this->view . '.create',
            compact('VENDOR')
        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'vendor_id'         => ['required'],
                'tad_id'         => ['required'],
                'sk'             => ['required', 'unique:trans_reward,sk'],
                'jenis_id'       => ['required', 'exists:ref_ehc_jenis_reward,sandi'],
                'tanggal_reward' => ['required', 'date_format:d/m/Y'],
                'keterangan'     => ['required'],
                'uploads.*'      => ['required'],
                'unit_kerja_id'  => ['required'],
                'posisi_id'      => ['required'],
                'to'             => ['required'],
            ],
            [
                'tad_id'            => 'Personil',
            ]
        );
        return Reward::createByRequest($request);
    }

    public function edit($id)
    {
        $record = Reward::findOrFail($id);
        $user = auth()->user();
        $so_id = $user->position->org_struct_id ?? null;
        $VENDOR = Vendor::with(
            [
                'tad' => function ($q) use ($user, $so_id) {
                    $q
                        ->with(
                            [
                                'kepegawaian' => function ($q) {
                                    $q->with('unitKerja');
                                },
                                'jabatan'
                            ]
                        )
                        ->whereHas(
                            'kepegawaian',
                            function ($q) use ($so_id) {
                                $q
                                    ->whereNull('resign_at')
                                    ->whereNull('out_at')
                                    ->when(
                                        $so_id,
                                        function ($q) use ($so_id) {
                                            if ($so_id == env('APP_HC_ID')) {
                                            } else {
                                                $q->where('cabang_id', $so_id);
                                            }
                                        }
                                    );
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
                                    ->whereNull('resign_at')
                                    ->whereNull('out_at');
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
        $request->validate(
            [
                'tad_id'         => ['required'],
                'sk'             => ['required', 'unique:trans_reward,sk,' . $id],
                'jenis_id'       => ['required', 'exists:ref_ehc_jenis_reward,sandi'],
                'tanggal_reward' => ['required', 'date_format:d/m/Y'],
                'keterangan'     => ['required'],
                // 'uploads.*'      => ['required'],
            ],
            [
                'tad_id'            => 'Personil',
            ]
        );
        $record = Reward::findOrFail($id);
        return $record->updateByRequest($request);
    }
    public function destroy($id, Request $request)
    {
        return Reward::find($id)->deleteByRequest($request);
    }

    public function tracking($id)
    {
        $record = Reward::with('tad')->findOrFail($id);
        $this->setPrepare(
            [
                'title'  => 'Penghargaan | <span class="label label-xl label-danger label-inline text-nowrap">' . ($record->tad->nama) . ' | SK ' . $record->sk . '</span>',
            ]
        );
        $show_title = true;
        return $this->render(
            $this->view . '.tracking',
            compact('show_title', 'record')
        );
    }

    public function riwayat($id)
    {
        $record = Reward::with(
            [
                'logs' => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ]
        )->find($id);
        $this->setPrepare(
            [
                'title'  => 'Reward TAD | <span class="label label-xl label-danger label-inline text-nowrap">' . ($record->tad->nama) . ' | SK ' . $record->sk . '</span>',
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
        $record = Reward::findOrFail($id);
        return $record->handleApproveSave($request);
    }

    public function print($id)
    {
        $record = Reward::with('tad')->findOrFail($id);
        $render = $this->render(
            $this->view . '.print',
            [
                'record' => $record,
            ]
        );

        $pdf = PDF::loadHTML($render)
            ->setPaper('a4')
            ->setWarnings(false);
        return $pdf->stream('Reward TAD.pdf', 'Reward TAD');
    }
}
