<?php

namespace Modules\Pengajuan\Http\Controllers\Resign;

use App\Entities\EHC\Jabatan;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Master\Entities\KategoriVendor;
use Modules\Master\Entities\Resign\Reason;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Entities\Resign\Pegawai;
use Modules\Pengajuan\Entities\Resign\Pengajuan;
use Modules\Pengajuan\Http\Requests\PengajuanRequest;
use Yajra\DataTables\DataTables;

class PengajuanController extends Controller
{
    protected $module = 'resign_mutasi.resign';
    protected $route  = 'pengajuan.resign';
    protected $view   = 'pengajuan::resign.form';
    protected $perms  = 'resign_mutasi.resign';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view' => $this->view,
                'perms' => $this->perms,
                'title'  => 'Resign',
                'breadcrumb' => [
                    // 'Resign & Mutasi'         => url('pengajuan/tad/form'),
                    // 'Resign'  => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user = auth()->user();
        $so_id = $user->position->org_struct_id ?? null;
        $records = Pengajuan::with('so', 'vendor')
            ->when(
                !$user->cekDivisiHC(),
                function ($q) use ($so_id, $user) {
                    $q->where('unit_kerja_id', $so_id);
		      // ->orWhereHas(
                      //  'approvalsAll',
                      //  function ($q) use ($user) {
                      //      $q->where(
                      //          function ($qq) use ($user) {
                      //              $qq->where('user_id', auth()->user()->id)
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
                    $q
                        ->where('unit_kerja_id', $user->vendor_id)
                        ->where('unit_kerja_type', Vendor::class);
                }
            )
	    ->when(
                $personil = request()->personil,
                function ($q) use ($personil) {
                    $q->where('personil', 'LIKE', '%' . $personil . '%');
                }
            )
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike(['no_tiket', 'created_at'], $keyword);
                }
            )
            ->when(
                $location_id = request()->location_id,
                function ($q) use ($location_id) {
                    $q->where('unit_kerja_id', $location_id);
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
            ->orderBy('updated_at', 'DESC');

        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'pegawai',
                function ($r) {
                    return $this->render(
                        'pengajuan::resign.partials.tad',
                        ['r' => $r]
                    );
                }
            )
            ->editColumn(
                'unit_kerja',
                function ($r) {
                    return '<span>' . $r->getUnitKerjaName() . '</span>';
                }
            )
            ->editColumn(
                'jabatan',
                function ($r) {
                    return '';
                }
            )
            ->editColumn(
                'pengajuan',
                function ($r) {
                    return '<span>' . $r->no_tiket . '</span><br><span>' . $r->tgl_pengajuan->translatedFormat('d/m/Y') . '</span>';
                }
            )
            ->editColumn(
                'lampiran',
                function ($r) {
                    return '<span>' . $r->files->count() . " Berkas" . '</span>';
                }
            )
            ->editColumn(
                'status',
                function ($record) {
                    // return $r->active_logs->statusRaw();
                    // if ($record->status == 1 || $record->status == 'submit') {
                    //     return '<spanbadge badge-primary text-white">Waiting Approval</span></span>';
                    //     return '<spanbadge badge-primary text-white">Submit</span></span>';
                    // } elseif (in_array($record->status, [0, 'draft'])) {
                    //     return '<spanbadge badge-warning text-white">Draft</span></span>';
                    // } elseif ($record->status == 'rejected') {
                    //     return '<spanbadge badge-danger text-white">Ditolak</span></span>';
                    // } elseif ($record->status == 'approved' || $record->status == 'completed') {
                    //     return '<spanbadge badge-success text-white">Completed</span></span>';
                    // }
                    // return $record->status;
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
                function ($r) {
                    $buttons = [];
                    $buttons[] = 'type:show|label:Detail|page:true|id:' . $r->id;
                    if (auth()->user()->checkPerms($this->perms . '.edit') && in_array($r->status, [0, 'draft', 'rejected'])) {
                        $buttons[] = 'type:edit|page:true|id:' . $r->id;
                    }
                    // if (auth()->user()->checkPerms($this->perms . '.approve') && in_array($r->status, ['1', 'submit']) && $r->checkApproval()) {
                    if (auth()->user()->checkPerms($this->perms . '.approve') && in_array($r->status, ['1', 'submit', 'waiting.approval']) && $r->checkApproval()) {
                        $buttons[] = [
                            'type' => 'show',
                            'page' => true,
                            'id' => $r->id,
                            'label' => 'Approval',
                            'icon' => 'fa fa-check text-primary',
                            'url' => route($this->route . '.approval', $r->id),
                        ];
                    }
                    if ($r->status === 'completed') {
                        $buttons[] = 'type:print|page:true|id:' . $r->id;
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
                    if (auth()->user()->checkPerms($this->perms . '.delete') && in_array($r->status, ['draft', 'rejected'])) {
                        $buttons[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus No Tiket <b>' . $r->no_tiket . '?</b>"';
                    }
                    return $this->makeDropdown($buttons);
                }
            )
            ->rawColumns(['action', 'pengajuan', 'created_at', 'status', 'unit_kerja', 'jabatan', 'lampiran'])
            ->make(true);
    }

    public function index()
    {
        $this->setPrepare(
            [
                // 'perms' => $this->perms.'.index',
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:pengajuan|label:Pengajuan Resign|sortable:false|className:text-center'),
                        $this->makeColumn('name:pegawai|label:Pegawai|sortable:false|className:text-center'),
                        $this->makeColumn('name:unit_kerja|label:Unit Kerja|sortable:false|className:text-center'),
                        $this->makeColumn('name:lampiran|label:Lampiran|sortable:false|className:text-center'),
                        $this->makeColumn('name:status|label:Status|sortable:false'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );
        return $this->render($this->view . '.index');
    }

    public function create()
    {
        $POSISI_TAD = Jabatan::whereHas('personils')
            ->orderBy('NM_UNIT', 'ASC')
            ->get();
        $struct = OrgStruct::groupByLevel([], true);
        return $this->render(
            $this->view . '.create',
            compact('POSISI_TAD', 'struct')
        );
    }

    public function store(PengajuanRequest $request)
    {
        return Pengajuan::createByRequest($request);
    }

    public function show($id)
    {
        $form = Pengajuan::findOrFail($id);
        $this->setPrepare(
            [
                'tableStruct' => [
                    'url' => route($this->route . '.resign.grid', $form->id),
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:nama|label:Nama|sortable:false|className:text-center'),
                        $this->makeColumn('name:nio|label:NIO|sortable:false|className:text-center'),
                        $this->makeColumn('name:vendor|label:Vendor|sortable:false|className:text-center'),
                        $this->makeColumn('name:jabatan|label:Jabatan|sortable:false|className:text-center'),
                        $this->makeColumn('name:tgl_resign|label:Tgl Resign|sortable:false|className:text-center'),
                        $this->makeColumn('name:tgl_efektif|label:Tgl Efektif|sortable:false|className:text-center'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );
        return $this->render($this->view . '.show', ['record' => $form]);
    }

    public function resignGrid(Request $request, $id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $records = $pengajuan->pegawai;
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'nama',
                function ($r) {
                    $html = '<div class="d-flex align-items-center">';
                    $html .= '<div class="symbol symbol-30 symbol-circle mr-2" data-toggle="tooltip" title="' . $r->nama . '">';

                    if (isset($r->file->foto_filepath) && \Storage::exists($r->file->foto_filepath)) {
                        $html .= '<img alt="Pic" src="' . url('storage/' . $r->file->foto_filepath) . '" />';
                    } else {
                        $html .= '<span class="symbol-label font-size-h5">' . $r->nama[0] . '</span>';
                    }

                    $html .= '</div>';
                    $html .= $r->nama;
                    $html .= '</div>';

                    return $html;
                }
            )
            ->editColumn(
                'nio',
                function ($r) {
                    return $r->kepegawaian->nio;
                }
            )
            ->editColumn(
                'vendor',
                function ($r) {
                    return $r->vendor->nama;
                }
            )
            ->editColumn(
                'jabatan',
                function ($r) {
                    return $r->jabatan->NM_UNIT;
                }
            )
            ->editColumn(
                'tgl_resign',
                function ($r) {
                    return Carbon::parse($r->pivot->tanggal_resign)->format('d/m/Y');
                }
            )
            ->editColumn(
                'tgl_efektif',
                function ($r) {
                    return Carbon::parse($r->pivot->tanggal_efektif)->format('d/m/Y');
                }
            )
            ->editColumn(
                'action',
                function ($r) {
                    $actions = [];
                    $actions[] = [
                        'type'      => 'show',
                        'id' => $r->id,
                        'url' => route($this->route . '.resign.grid.show', $r->id)
                    ];
                    return $this->makeDropdown($actions);
                }
            )
            ->rawColumns([
                'num',
                'nama',
                'nio',
                'vendor',
                'jabatan',
                'tgl_resign',
                'tgl_efektif',
                'action',
            ])
            ->make(true);
    }

    public function resignGridShow(Tad $pengajuan)
    {
        return $this->render(
            $this->view . '.gridShow',
            compact('pengajuan')
        );
    }

    public function edit($id)
    {
	$form = Pengajuan::find($id);
    
    	if (!$form) {
        	// Handle jika data tidak ditemukan
        	abort(404);
    	}	

        $reason = Reason::all();
        $POSISI_TAD = Jabatan::whereHas('personils')
            ->orderBy('NM_UNIT', 'ASC')
            ->get();
        $struct = OrgStruct::groupByLevel([], true);
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Resign',
                'breadcrumb' => [
                    'Edit Data Pengajuan' => '/',
                ]
            ]
        );
        return $this->render(
            $this->view . '.edit',
            [
                'pengajuan'     => $form,
                'POSISI_TAD'    => $POSISI_TAD,
                'struct'        => $struct,
                'reason'        => $reason
            ]
        );
    }

    public function update(PengajuanRequest $request, $id)
    {
	$form = Pengajuan::find($id);

    	if (!$form) {
        	abort(404);
    	}
        return $form->updateByRequest($request);    
    }

    public function destroy($id, Request $request)
    {
	$form = Pengajuan::find($id);
        return $form->deleteByRequest($request);
    }

    public function pegawaiDelete($id, $ud = 'ud')
    {
        // dd(219, $id, $ud);
        $pegawai = Pegawai::find($id);
        return $pegawai->deleteByRequest();
    }

    public function riwayat($id)
    {
        $record = Pengajuan::with('logs')->find($id);
        $this->setPrepare(
            [
                'title'  => 'Pengajuan Resign | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->no_tiket . '</span>',
            ]
        );
        $show_title = true;
        return $this->render(
            $this->view . '.riwayat',
            compact('record', 'show_title')
        );
    }

    public function approval($id)
    {
        $form = Pengajuan::findOrFail($id);
        $this->setPrepare(
            [
                'tableStruct' => [
                    'url' => route($this->route . '.resign.grid', $form->id),
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:nama|label:Nama|sortable:false|className:text-center'),
                        $this->makeColumn('name:nio|label:NIO|sortable:false|className:text-center'),
                        $this->makeColumn('name:vendor|label:Vendor|sortable:false|className:text-center'),
                        $this->makeColumn('name:jabatan|label:Jabatan|sortable:false|className:text-center'),
                        $this->makeColumn('name:tgl_resign|label:Tgl Resign|sortable:false|className:text-center'),
                        $this->makeColumn('name:tgl_efektif|label:Tgl Efektif|sortable:false|className:text-center'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );

        return $this->render($this->view . '.approval', ['record' => $form]);
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
        $record = Pengajuan::findOrFail($id);
        return $record->handleApproveSave($request);
    }

    public function tracking($id)
    {
        $record = Pengajuan::with('pegawai')->findOrFail($id);

        $this->setPrepare(
            [
                'title'  => 'Pengajuan Resign | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->no_tiket . '</span>',
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
        $pengajuan = Pengajuan::with('pegawai')->findOrFail($id);

        $render = $this->render(
            $this->view . '.print',
            [
                'record' => $pengajuan,
            ]
        );

        $pdf = Pdf::loadHTML($render)
            ->setPaper('a4')
            // ->setOptions(['isHtml5ParserEnabled' => true])
            ->setWarnings(false);

        return $pdf->stream('Pengajuan Resign TAD.pdf', 'Pengajuan Resign TAD');
    }
}
