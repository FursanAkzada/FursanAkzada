<?php

namespace Modules\Penilaian\Http\Controllers\Vendor;

use App\Entities\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Master\Entities\KategoriVendor;
use Modules\Master\Entities\Penilaian\PertanyaanVendor;

use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;
use Modules\Penilaian\Entities\Vendor\Penilaian;
use Yajra\DataTables\DataTables;

class PenilaianController extends Controller
{
    protected $module = 'penilaian.vendor';
    protected $route  = 'penilaian.vendor';
    protected $view   = 'penilaian::vendor.penilaian';
    protected $perms  = 'penilaian.vendor';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'perms'  => $this->perms,
                'route'  => $this->route,
                'title'  => 'Penilaian Vendor',
                'breadcrumb' => [
                    'Penilaian'     => route($this->route . '.index'),
                    'Penilaian Vendor' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $user               = auth()->user();
        $so_id              = $user->position->org_struct_id ?? null;
        $vendor_id          = $request->vendor_id;
        $unit_kerja_id      = $request->location_id;
        $tahun              = $request->tahun;
        $semester           = $request->semester;
        $has_approve_perm   = $user->checkPerms($this->perms . '.approve');
        $records = Penilaian::with('vendor', 'creator.position')
            ->when(
                $user->isEhc && !$user->cekDivisiHC() && ($so_id),
                function ($q) use ($so_id, $user) {
                    $q->where('unit_kerja_id', $so_id);
		      // ->orWhereHas(
                      //  'approvalsAll',
                      //  function ($qq) use ($user) {
                      //      $qq->where(
                      //          function ($qqq) use ($user) {
                      //              $qqq->where('user_id', auth()->user()->id)
                      //                  ->whereIn('group_id', auth()->user()->getGroupIds());
                      //          }
                      //      );
                      //  }
                   // );
                }
            )
            ->when(
                $user->isVendor,
                function ($q) use ($user) {
                    $q->whereIn('status', ['approved', 'completed']);
                }
            )
            ->when(
                $tahun,
                function ($q) use ($tahun) {
                    $q->where('tahun', $tahun);
                }
            )
            ->when(
                $unit_kerja_id,
                function ($q) use ($unit_kerja_id) {
                    $q->where('unit_kerja_id', $unit_kerja_id);
                }
            )
            ->when(
                $semester,
                function ($q) use ($semester) {
                    $q->where('semester', $semester);
                }
            )
            ->when(
                $vendor_id,
                function ($q) use ($vendor_id) {
                    $q->where('vendor_id', $vendor_id);
                }
            )
            ->when(
                $date_start = request()->date_start,
                function ($qq) use ($date_start) {
                    $qq->where('tgl_penilaian', '>=', Carbon::createFromFormat('d/m/Y', $date_start)->format('Y-m-d'));
                }
            )
            ->when(
                $date_end = request()->date_end,
                function ($qq) use ($date_end) {
                    $qq->where('tgl_penilaian', '<=', Carbon::createFromFormat('d/m/Y', $date_end)->format('Y-m-d'));
                }
            )
            ->orderBy('trans_penilaian_vendor.updated_at', 'DESC')->get();
        // dd(118, $tahun);
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'unit_kerja',
                function ($r) {
                    return $r->unitKerja->name;
                }
            )
            ->editColumn(
                'vendor.nama',
                function ($r) {
                    return $r->vendor->nama;
                }
            )
            ->editColumn(
                'periode',
                function ($r) {
                    return $r->tahun . ' / ' . $r->semester;
                }
            )
            ->editColumn(
                'tgl_penilaian',
                function ($r) {
                    return $r->no_surat . "<br>" . $r->tgl_penilaian->format('d/m/Y');
                }
            )
            ->editColumn(
                'skor',
                function ($r) {
                    return $r->jawaban->sum('value');
                }
            )
            ->editColumn(
                'kesimpulan',
                function ($r) {
                    return $r->kesimpulan;
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
                'created_at',
                function ($r) {
                    return $r->createdByRaw2();
                }
            )
            ->editColumn(
                'review',
                function ($r) {
                    if (!empty($r->review->user)) {
                        return '<span class=""> Telah di review ' . $r->review->user->name . '</span>';
                    }
                    return '<span class="">Belum Mengetahui</span>';
                }
            )
            ->editColumn(
                'action',
                function ($r) use ($user) {
                    $buttons = [];
                    $buttons[] = 'type:show|label:Detail|page:true|id:' . $r->id;
                    if ($user->checkPerms($this->perms . '.edit') && in_array($r->status, [0, 'draft', 'rejected'])) {
                        $buttons[] = [
                            'type' => 'edit',
                            'page' => true,
                            'id' => $r->id,
                            'url' => route($this->route . '.edit', $r->id),
                        ];
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
                        $buttons[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Yakin Menghapus Data Penilaian Vendor ?"';
                    }
                    if ($user->checkPerms($this->perms . '.view') && !in_array($r->status, [0, 'draft'])) {
                        $buttons[] = [
                            'type' => 'show',
                            'icon' => 'fas fa-chart-line text-info',
                            'label' => 'Tracking Approval',
                            'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                            'url' => route($this->route . '.tracking', $r->id),
                            'id' => $r->id,
                        ];
                    }
                    if ($r->status === 'approved' || $r->status === 'completed' || $r->status === 'submit') {
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
            ->rawColumns(['unit_kerja', 'action', 'created_at', 'status', 'tgl_penilaian', 'vendor.nama', 'periode', 'skor', 'kesimpulan'])
            ->make(true);
    }

    public function index()
    {
        // return 11;
        $this->setPrepare(
            [
                'perms' => $this->perms . '.view',
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:periode|label:Periode|sortable:false|className:text-center'),
                        $this->makeColumn('name:unit_kerja|label:Unit Kerja|sortable:false|className:text-center'),
                        $this->makeColumn('name:tgl_penilaian|label:Penilaian|sortable:false|className:text-center'),
                        $this->makeColumn('name:vendor.nama|label:Vendor|sortable:false|className:text-left'),
                        $this->makeColumn('name:skor|label:Skor|sortable:false|className:text-center'),
                        $this->makeColumn('name:kesimpulan|label:Kesimpulan|sortable:false|className:text-center'),
                        $this->makeColumn('name:status|label:Status|sortable:false|className:text-center'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );
        $QUOTA_PERIODE = QuotaPeriode::orderBy('year', 'ASC')->get();
        $VENDOR = Vendor::orderBy('nama', 'ASC')
            ->get();
        $struct = OrgStruct::where('level', '!=', 'root')
            ->get()
            ->groupBy('level');
        return $this->render(
            $this->view . '.index',
            compact('QUOTA_PERIODE', 'VENDOR', 'struct')
        );
    }

    public function create()
    {
        $QUOTA_PERIODE = QuotaPeriode::orderBy('year', 'ASC')->get();
        $VENDOR = Vendor::orderBy('nama', 'ASC')
            ->get();
        return $this->render(
            $this->view . '.create',
            [
                'VENDOR'        => $VENDOR,
                'pertanyaan'    => \Modules\Master\Entities\Pertanyaan\Vendor::get(),
                'QUOTA_PERIODE' => $QUOTA_PERIODE,
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'vendor_id'     => ['required'],
                'tahun'         => ['required'],
                'semester'      => ['required'],
                'kesimpulan'    => ['required'],
                'kritik'        => ['required'],
                'saran'         => ['required'],
                'tgl_penilaian' => [
                    'required',
                    'date_format:d/m/Y',
                    'before_or_equal:tanggal_sekarang',
                ],
                'to'            => ['required'],
                'no_surat'      => ['required'],
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
        return Penilaian::createByRequest($request);
    }

    public function show($id)
    {
        $penilaian = Penilaian::findOrFail($id);
        $data = [
            'vendor' => Vendor::get(),
            'record' => $penilaian,
            'pertanyaan' => \Modules\Master\Entities\Pertanyaan\Vendor::with(
                [
                    'jawaban' => function ($query) use ($penilaian) {
                        return $query->where('penilaian_vendor_id', $penilaian->id);
                    }
                ]
            )->get()
        ];

        return $this->render($this->view . '.show', $data);
    }

    public function edit($id)
    {
        $penilaian = Penilaian::findOrFail($id);
        $QUOTA_PERIODE = QuotaPeriode::orderBy('year', 'ASC')->get();
        $VENDOR = Vendor::orderBy('nama', 'ASC')->get();
        return $this->render(
            $this->view . '.edit',
            [
                'vendor'        => Vendor::get(),
                'record'        => $penilaian,
                'QUOTA_PERIODE' => $QUOTA_PERIODE,
                'VENDOR'        => $VENDOR,
                'pertanyaan' => \Modules\Master\Entities\Pertanyaan\Vendor::with(
                    [
                        'jawaban' => function ($query) use ($penilaian) {
                            return $query->where('penilaian_vendor_id', $penilaian->id);
                        }
                    ]
                )->get()
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'vendor_id'     => ['required'],
                'tahun'         => ['required'],
                'kesimpulan'    => ['required'],
                'kritik'        => ['required'],
                'saran'         => ['required'],
                'to'            => ['required'],
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
        $record = Penilaian::find($id);
        return $record->updateByRequest($request);
    }

    public function destroy($id, Request $request)
    {
	$penilaian = Penilaian::findOrFail($id);
        return $penilaian->deleteByRequest($request);
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
                'title'  => 'Penilaian Vendor | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->vendor->nama . ' Tahun ' . $record->tahun . '</span> / ' . $record->semester,
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
        $record = Penilaian::where('id', $id)->first();
        $this->setPrepare(
            [
                'title'  => 'Penilaian Vendor | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->vendor->nama . '</span>',
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
        $record = Penilaian::with('vendor', 'so', 'jawaban.pertanyaan', 'creator', 'approvals')->findOrFail($id);

        // dd(json_decode($record));
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

        return $pdf->stream('Penilaian Vendor.pdf', 'Penilaian Vendor');
    }
}
