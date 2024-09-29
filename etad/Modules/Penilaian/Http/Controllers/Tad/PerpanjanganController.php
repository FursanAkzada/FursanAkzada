<?php

namespace Modules\Penilaian\Http\Controllers\Tad;

use App\Http\Controllers\Controller;
use App\Support\Base;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;
use Modules\Penilaian\Entities\Tad\Perpanjangan;
use Yajra\DataTables\DataTables;

class PerpanjanganController extends Controller
{
    protected $module   = 'penilaian.perpanjangan';
    protected $route    = 'penilaian.perpanjangan';
    protected $view     = 'penilaian::perpanjangan';
    protected $perms    = 'penilaian.perpanjangan';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view' => $this->view,
                'perms' => $this->perms,
                'title'  => 'Perpanjangan Kontrak',
                'breadcrumb' => [
                    'Penilaian'     => url('penilaian/perpanjangan'),
                    'Perpanjangan Kontrak' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $user       = auth()->user();
        $so_id      = $user->position->org_struct_id ?? null;
        $records    = Perpanjangan::with('tad', 'tad.kepegawaian', 'unitKerja', 'tad.kepegawaian.unitKerja', 'kepegawaian', 'kepegawaian.unitKerja', 'kepegawaian.vendor', 'kepegawaian.jabatan')
            ->when(
                $user->isEhc && !$user->cekDivisiHC() && ($so_id),
                function ($qqq) use ($so_id, $user) {
                    $qqq->where('unit_kerja_id', $so_id);
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
                $no_pengajuan = $request->no_pengajuan,
                function ($q) use ($no_pengajuan) {
                    $q->where('no_pengajuan', 'LIKE', '%' . $no_pengajuan . '%');
                }
            )
	    ->when(
                $unit_kerja_id = request()->unit_kerja_id,
                function ($q) use ($unit_kerja_id) {
                    $q->where('unit_kerja_id', $unit_kerja_id);
                }
            )
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike(['kepegawaian.nio'], $keyword);
                }
            )
            ->when(
                $date_start = request()->date_start,
                function ($qq) use ($date_start) {
                    $qq->where('tgl_pengajuan', '>=', Carbon::createFromFormat('d/m/Y', $date_start)->format('Y-m-d'));
                }
            )
            ->when(
                $date_end = request()->date_end,
                function ($qq) use ($date_end) {
                    $qq->where('tgl_pengajuan', '<=', Carbon::createFromFormat('d/m/Y', $date_end)->format('Y-m-d'));
                }
            )
            // ->when(
            //    $user->checkPerms($this->perms . '.approve'),
            //    function ($q) use ($so_id) {
            //        $q->orWhereIn('status', ['draft', 'submit', 'rejected', 'approved', 'waiting.approval', 'completed']);
            //    }
            // )
            // ->orWhere('created_by', $user->id)
            ->orderBy('trans_perpanjangan_tad.updated_at', 'DESC')->get();

        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'pengajuan',
                function ($r) {
                    return $r->no_pengajuan . "<br>" . $r->tgl_pengajuan->translatedFormat('d F Y');
                }
            )
            ->editColumn(
                'tad',
                function ($r) {
                    return $r->tad->nama . "<br>" . $r->kepegawaian->unitKerja->name;
                }
            )
            ->editColumn(
                'kontrak_lama',
                function ($r) {
                    $in_at = null;
                    $contract_due = null;
                    if ($r->kepegawaian->in_at && $r->kepegawaian->contract_due) {
                        return $r->getTanggalFormatMulaiSelesai($r->kepegawaian->in_at, $r->kepegawaian->contract_due, ' s/d ');
                    }
                    if ($r->tad->date_old_contract && $r->kepegawaian->in_at) {
                        return $r->getTanggalFormatMulaiSelesai($r->kepegawaian->in_at, $r->tad->date_old_contract, ' s/d ');
                    }
                    return '-';
                }
            )
            ->editColumn(
                'kontrak_baru',
                function ($r) {
                    return $r->tgl_awal_kontrak_baru->translatedFormat('d M Y')
                    .' s/d '.$r->tgl_akhir_kontrak_baru->translatedFormat('d M Y');
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
                            'url' => route($this->route . '.approval', $r->id),
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
                        $buttons[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Yakin Menghapus Data Perpanjangan Kontrak ?"';
                    }
                    if ($r->status == 'completed' || $r->status == 'waiting.approval') {
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
            ->rawColumns(['tad', 'status', 'action', 'created_at', 'pengajuan', 'kontrak_lama', 'kontrak_baru'])
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
                        $this->makeColumn('name:pengajuan|label:Perpanjangan Kontrak|sortable:false|className:text-center'),
                        $this->makeColumn('name:tad|label:Personil|sortable:false|className:text-center'),
                        $this->makeColumn('name:kontrak_lama|label:Tgl Kontrak Lama|sortable:false|className:text-center'),
                        $this->makeColumn('name:kontrak_baru|label:Tgl Kontrak Baru|sortable:false|className:text-center'),
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
        return $this->render($this->view . '.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'tad_id'                    => ['required'],
                'to'                        => ['required'],
                'no_pengajuan'              => ['required'],
                'tgl_pengajuan'             => [
                    'required',
                    'date_format:d/m/Y',
                    'before_or_equal:tanggal_sekarang',
                ],
                'unit_kerja_id'             => ['required'],
                // 'nio'                       => ['required'],
                'vendor_id'                 => ['required'],
                'position_id'               => ['required'],
                // 'tgl_akhir_kontrak_lama'    => ['required'],
                'tgl_awal_kontrak_baru'     => [
                    'required',
                    'date_format:d/m/Y',
                    'after:tgl_akhir_kontrak_lama',
                    'before:tgl_akhir_kontrak_baru',
                ],
                'tgl_akhir_kontrak_baru'    => [
                    'required',
                    'date_format:d/m/Y',
                    'after:tgl_awal_kontrak_baru',
                ],
                'keterangan'                => ['required'],
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
        // \DB::beginTransaction();
        $record = Perpanjangan::firstOrCreate(['no_pengajuan' => $request->no_pengajuan]);
        return $record->createOrUpdateByRequest($request, $record);
    }

    public function show($id)
    {
        $user = auth()->user();
        $so_id = $user->position->org_struct_id ?? null;
        $record = Perpanjangan::with('tad', 'unitKerja', 'tad.kepegawaian', 'tad.kepegawaian.unitKerja')
            ->findOrFail($id);
        return $this->render(
            $this->view . '.show',
            compact('record')
        );
    }


    public function edit($id)
    {
        $user = auth()->user();
        $so_id = $user->position->org_struct_id ?? null;
        $record = Perpanjangan::with('tad', 'unitKerja', 'tad.kepegawaian', 'tad.kepegawaian.unitKerja')
            ->findOrFail($id);
        return $this->render(
            $this->view . '.edit',
            compact('record')
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'tad_id'          => ['required'],
                'to'                => ['required'],
                // 'no_pengajuan'          => ['required'],
                'tgl_pengajuan'          => [
                    'required',
                    'date_format:d/m/Y',
                    'before_or_equal:tanggal_sekarang',
                ],
                'unit_kerja_id'          => ['required'],
                'tgl_akhir_kontrak_lama'    => ['required'],
                'tgl_awal_kontrak_baru'     => [
                    'required',
                    'date_format:d/m/Y',
                    'after:tgl_akhir_kontrak_lama',
                    'before:tgl_akhir_kontrak_baru',
                ],
                'tgl_akhir_kontrak_baru'    => [
                    'required',
                    'date_format:d/m/Y',
                    'after:tgl_awal_kontrak_baru',
                ],
                'keterangan'          => ['required'],
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
        // \DB::beginTransaction();
        $record = Perpanjangan::find($id);
        return $record->createOrUpdateByRequest($request, $record);
    }

    public function destroy($id, Request $request)
    {
        // dd(321, $id);
        $record = Perpanjangan::findOrFail($id);
        return $record->deleteByRequest($request);
    }

    public function approval($id)
    {
        $user = auth()->user();
        $so_id = $user->position->org_struct_id ?? null;
        $record = Perpanjangan::with('tad', 'unitKerja', 'tad.kepegawaian', 'tad.kepegawaian.unitKerja')
            ->findOrFail($id);
        return $this->render(
            $this->view . '.approval',
            compact('record')
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
        $record = Perpanjangan::with('tad')->where('id', $id)->first();
        return $record->handleApproveSave($request, $record);
    }

    public function tracking($id)
    {
        $record = Perpanjangan::with('tad')->where('id', $id)->first();
        $this->setPrepare(
            [
                'title'  => 'Perpanjangan TAD | <span class="label label-xl label-danger label-inline text-nowrap">' . ($record->tad->nama) . '</span>',
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
        $record = Perpanjangan::with('tad')->find($id);
        $this->setPrepare(
            [
                'title'  => 'Perpanjangan TAD | <span class="label label-xl label-danger label-inline text-nowrap">' . ($record->tad->nama) . '</span>',
            ]
        );
        $show_title = true;
        return $this->render(
            $this->view . '.riwayat',
            compact('record', 'show_title')
        );
    }

    public function print($id)
    {
        $record = Perpanjangan::with('tad', 'tad.kepegawaian', 'unitKerja', 'tad.kepegawaian.unitKerja', 'kepegawaian', 'kepegawaian.unitKerja', 'kepegawaian.vendor', 'kepegawaian.jabatan')->where('id', $id)->first();

        $render = $this->render(
            $this->view . '.print',
            [
                'record' => $record,
            ]
        );

        $pdf = PDF::loadHTML($render)
            ->setPaper('a4')
            // ->setOptions(['isHtml5ParserEnabled' => true])
            ->setWarnings(false);

        return $pdf->stream('Perpanjangan Kontrak.pdf', 'Perpanjangan Kontrak');
    }
}