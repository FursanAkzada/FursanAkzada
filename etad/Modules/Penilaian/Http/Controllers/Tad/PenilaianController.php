<?php

namespace Modules\Penilaian\Http\Controllers\Tad;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Master\Entities\Pertanyaan\Tad as PertanyaanTad;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;
use Modules\Penilaian\Entities\Tad\Penilaian;
use Yajra\DataTables\DataTables;

class PenilaianController extends Controller
{
    protected $module = 'penilaian.tad';
    protected $route  = 'penilaian.tad';
    protected $view   = 'penilaian::tad.form';
    protected $perms  = 'penilaian.tad';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'perms'  => $this->perms,
                'route'  => $this->route,
                'view' => $this->view,
                'title'  => 'Penilaian TAD',
                'breadcrumb' => [
                    'Penilaian'     => route($this->route . '.index'),
                    'Penilaian TAD' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $user           = auth()->user();
        $so_id          = $user->position->org_struct_id ?? null;
        $vendor_id      = $request->vendor_id;
        $records    = Penilaian::with('tad.kepegawaian', 'so', 'vendor', 'creator')
            ->when(
                $user->isEhc && !$user->cekDivisiHC() && ($so_id),
                function ($qqq) use ($so_id, $user) {
                    // kepegawaian->unitKerja
                    $qqq->whereHas('kepegawaian', function($kp) use ($so_id, $user) {
                        $kp->whereHas('unitKerja', function($kpp) use ($so_id, $user) {
                            $kpp->where('id', $so_id);
                        });
                    });
			// ->orWhereHas(
                        // 'approvalsAll',
                        // function ($q) use ($user) {
                        //    $q->where(
                        //        function ($qq) use ($user) {
                        //            $qq->where('user_id', auth()->user()->id)
                        //                ->whereIn('group_id', auth()->user()->getGroupIds());
                        //        }
                        //    );
                        // }
                    // );
                }
            )
            ->when(
                $user->isVendor,
                function ($q) use ($user) {
                    $q->whereHas(
                        'creator',
                        function ($q) use ($user) {
                            $q->where('vendor_id', $user->vendor_id);
                        }
                    );
                },
            )
	    ->when(
                $personil = $request->personil,
                function ($q) use ($personil) {
                    $q->where('personil', 'LIKE', '%' . $personil . '%');
                }
            )
	    ->when(
                $location_id = request()->location_id,
                function ($q) use ($location_id) {
                    $q->where('unit_kerja_id', $location_id);
                }
            )
	    ->when(
                $vendor_id,
                function ($q) use ($vendor_id) {
                    $q->where('vendor_id', $vendor_id);
                }
            )
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike(['kepegawaian.nio'], $keyword);
                }
            )
            ->when(
                $year = request()->tahun,
                function ($qq) use ($year) {
                    $qq->where('tahun', $year);
                }
            )
            ->when(
                $semester = request()->semester,
                function ($q) use ($semester) {
                    $q->where('semester', $semester);
                }
            )
            ->when(
                $date_start = request()->date_start,
                function ($qq) use ($date_start) {
                    $qq->where('periode_penilaian', '>=', Carbon::createFromFormat('d/m/Y', $date_start)->format('Y-m-d'));
                }
            )
            ->when(
                $date_end = request()->date_end,
                function ($qq) use ($date_end) {
                    $qq->where('periode_penilaian', '<=', Carbon::createFromFormat('d/m/Y', $date_end)->format('Y-m-d'));
                }
            )
            ->orderBy('trans_penilaian_tad.updated_at', 'DESC')->get();

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
                    return $this->render($this->view . '.partials.grid.nama', compact('r'));
                }
            )
            ->editColumn(
                'periode',
                function ($r) {
                    return '<span class="">' . $r->tahun . ' / ' . $r->semester . '</span>';
                }
            )
            ->editColumn(
                'tgl_penilaian',
                function ($r) {
                    return '<span class="">' . $r->periode_penilaian->format('d/m/Y') . "</span>";
                }
            )
            ->editColumn(
                'penilai',
                function ($r) {
                    return '<span class="">' . $r->tad->vendor->nama . '</span>';
                }
            )
            ->editColumn(
                'status',
                function ($record) {
                    return $record->labelStatus($record->status ?? 'new');
                }
            )
            ->editColumn(
                'created_at',
                function ($r) {
                    return $r->createdByRaw2();
                }
            )
            ->editColumn(
                'action',
                function ($r) use ($user) {
                    $buttons = [];
                    $buttons[] = 'type:show|label:Detail|page:true|id:' . $r->id;
                    if ($user->checkPerms($this->perms . '.edit') && in_array($r->status, [0, 'draft', 'rejected'])) {
                        $buttons[] = 'type:edit|page:true|id:' . $r->id;
                    }
                    if ($user->checkPerms($this->perms . '.approve') && in_array($r->status, ['1', 'submit', 'waiting.approval']) && $r->checkApproval()) {
                        $buttons[] = [
                            'type' => 'show',
                            'page' => true,
                            'id' => $r->id,
                            'label' => 'Approval',
                            'icon' => 'fa fa-check text-primary',
                            'url' => route($this->route . '.show', $r->id),
                        ];
                    }
                    if (!in_array($r->status, ['new', 'draft'])) {
                        $buttons[] = [
                            'type' => 'show',
                            'icon' => 'fas fa-chart-line text-info',
                            'label' => 'Tracking Approval',
                            'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                            'url' => route($this->route . '.tracking', $r->id),
                            'id' => $r->id,
                        ];
                    }

                    if ($user->checkPerms($this->perms . '.delete') && in_array($r->status, [0, 'draft', 'rejected'])) {
                        $buttons[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Yakin Menghapus Data Penilaian TAD ?"';
                    }
                    if ($r->status == 'completed') {
                        $buttons[] = 'type:print|page:true|id:' . $r->id;
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
            ->rawColumns(['penilai', 'unit_kerja', 'status', 'action', 'created_at', 'nama', 'penilaian', 'periode', 'tgl_penilaian'])
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
                        $this->makeColumn('name:periode|label:Periode|sortable:false|className:text-center'),
                        // $this->makeColumn('name:nio|label:Nomor Induk|sortable:false'),
                        $this->makeColumn('name:tad.nama|label:Personil|sortable:false|className:text-left'),
                        // $this->makeColumn('name:unit_kerja|label:Unit Kerja|sortable:false|className:text-left'),
                        $this->makeColumn('name:penilai|label:Vendor|sortable:false|className:text-center'),
                        $this->makeColumn('name:tgl_penilaian|label:Tgl Penilaian|sortable:false|className:text-center'),
                        $this->makeColumn('name:status|label:Status|sortable:false|className:text-center'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
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
            compact('VENDOR', 'struct')
        );
    }

    public function create()
    {
        $user = auth()->user();
        $so_id = $user->position->org_struct_id ?? null;
        $QUOTA_PERIODE = QuotaPeriode::orderBy('year', 'ASC')->get();
        $VENDOR = Vendor::with(
            [
                'tad' => function ($q) use ($user) {
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
        $pertanyaan = PertanyaanTad::whereDoesntHave('parent')->get();
        // dd(json_decode($VENDOR));
        return $this->render(
            $this->view . '.create',
            compact('QUOTA_PERIODE', 'VENDOR', 'pertanyaan')
        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'unit_kerja_id'     => ['required'],
                'vendor_id'         => ['required'],
                'posisi_id'         => ['required'],
                'personil'          => ['required'],
                'question.*'        => ['required'],
                // 'masa_kerja'        => ['required'],
                'periode_penilaian' => [
			'required',
                	'date_format:d/m/Y',
                    	'before_or_equal:tanggal_sekarang',
		],
                'tahun'             => ['required', 'date_format:Y'],
                'semester'          => ['required'],
                'masa_kontrak'      => ['nullable'],
                'prestasi'          => ['required'],
                'indisipliner'      => ['required'],
                'saran'             => ['required'],
                'to'                => ['required'],
                'no_surat'          => ['required'],
            ],
            [
                'before'            => 'Tanggal tidak valid',
                'before_or_equal'   => 'Tanggal tidak valid',
                'after'             => 'Tanggal tidak valid',
                'after_or_equal'    => 'Tanggal tidak valid',
            ],
            [
                'before'            => 'Tanggal tidak valid',
                'before_or_equal'   => 'Tanggal tidak valid',
                'after'             => 'Tanggal tidak valid',
                'after_or_equal'    => 'Tanggal tidak valid',
            ]
        );

        $request->request
            ->add(
                [
                    'tad_id'                => $request->personil,
                    'status_perpanjangan'   => 2,
                    'masa_kerja'            => '1',
                ]
            );
        return Penilaian::createByRequest($request);
    }

    public function show($id)
    {
        $record = Penilaian::findOrFail($id);
        $pertanyaan = PertanyaanTad::with(
            [
                'jawaban' => function ($query) use ($record) {
                    return $query->where('penilaian_id', $record->id);
                }
            ]
        )->whereDoesntHave('parent')->get();
        return $this->render(
            $this->view . '.show',
            compact('record', 'pertanyaan')
        );
    }


    public function edit($id)
    {
        $user = auth()->user();
        $so_id = $user->position->org_struct_id ?? null;
        $record = Penilaian::with('tad')->findOrFail($id);
        $QUOTA_PERIODE = QuotaPeriode::orderBy('year', 'ASC')->get();
        $VENDOR = Vendor::with(
            [
                'tad' => function ($q) use ($user) {
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
        $pertanyaan = PertanyaanTad::with(
            [
                'jawaban' => function ($query) use ($record) {
                    return $query->where('penilaian_id', $record->id);
                }
            ]
        )->whereDoesntHave('parent')->get();

        return $this->render(
            $this->view . '.edit',
            compact('QUOTA_PERIODE', 'VENDOR', 'record', 'pertanyaan')
        );
    }


    public function update(Request $request, $id)
    {
        $request->validate(
            [
		'vendor_id'     => ['required'],
                'personil'          => ['required'],
                'question.*'        => ['required'],
                'periode_penilaian' => [
			'required',
                    	'date_format:d/m/Y',
                    	'before_or_equal:tanggal_sekarang',
		],
                'tahun'             => ['required', 'date_format:Y'],
                'semester'          => ['required'],
                'masa_kontrak'      => ['nullable'],
                'prestasi'          => ['required'],
                'indisipliner'      => ['required'],
                'saran'             => ['required'],
                'to'                => ['required'],
            ],
            [
                'before'            => 'Tanggal tidak valid',
                'before_or_equal'   => 'Tanggal tidak valid',
                'after'             => 'Tanggal tidak valid',
                'after_or_equal'    => 'Tanggal tidak valid',
            ],
            [
                'before'            => 'Tanggal tidak valid',
                'before_or_equal'   => 'Tanggal tidak valid',
                'after'             => 'Tanggal tidak valid',
                'after_or_equal'    => 'Tanggal tidak valid',
            ]
        );
        $request->request
            ->add(
                [
                    'tad_id'                => $request->personil,
                    'status_perpanjangan'   => 2,
                    'masa_kerja'            => '1',
                ]
            );
        $record = Penilaian::with('tad')->findOrFail($id);
        $request->request
            ->add(
                [
                    'tad_id'                => $request->personil,
                    'masa_kerja'            => $record->masa_kerja,
                ]
            );
	$record = Penilaian::find($id);
        return $record->updateByRequest($request);
    }


    public function destroy($id, Request $request)
    {
        // dd(321, $id);
        $record = Penilaian::findOrFail($id);
        return $record->deleteByRequest($request);
    }

    public function _penilaianShow(Penilaian $penilaian)
    {
        $data = [
            'record' => $penilaian,
            'pertanyaan' => PertanyaanTad::with(
                ['jawaban' => function ($query) use ($penilaian) {
                    return $query->where('penilaian_id', $penilaian->id);
                }]
            )->whereDoesntHave('parent')->get()
        ];

        return $this->render($this->view . '.penilaian.show', $data);
    }

    public function riwayat($id)
    {
        $record = Penilaian::with(
            [
                'logs' => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ]
        )->find($id);
        $this->setPrepare(
            [
                'title'  => 'Penilaian TAD | <span class="label label-xl label-danger label-inline text-nowrap">' . ($record->tad->nama) . ' | Bulan ' . $record->periode_penilaian_label . ' Tahun ' . $record->tahun . '</span>',
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
        $record = Penilaian::findOrFail($id);
        return $record->handleApproveSave($request);
    }

    public function tracking($id)
    {
        $record = Penilaian::with('tad', 'so', 'creator', 'approvals')->findOrFail($id);
        $this->setPrepare(
            [
                'title'  => 'Penilaian TAD | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->tad->nama . '</span>',
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
        $record = Penilaian::with('tad', 'so', 'creator', 'approvals')->findOrFail($id);
        $pertanyaan = PertanyaanTad::with(
            [
                'jawaban' => function ($query) use ($record) {
                    return $query->where('penilaian_id', $record->id);
                }
            ]
        )->whereDoesntHave('parent')->get();
        // return $pertanyaan;

        // dd(json_decode($record));
        $render = $this->render(
            $this->view . '.print',
            compact('record', 'pertanyaan')
        );

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($render)
            ->setPaper('a4')
            // ->setOptions(['isHtml5ParserEnabled' => true])
            ->setWarnings(false);
        return $pdf->stream('Penilaian TAD.pdf', 'Penilaian TAD');
    }
}
