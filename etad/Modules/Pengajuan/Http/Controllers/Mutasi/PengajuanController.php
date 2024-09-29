<?php

namespace Modules\Pengajuan\Http\Controllers\Mutasi;

use App\Entities\EHC\Jabatan;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Mutasi\Pengajuan;
use Modules\Pengajuan\Http\Requests\PengajuanMutasiRequest;
use Yajra\DataTables\DataTables;

class PengajuanController extends Controller
{

    protected $module = 'resign_mutasi.mutasi';
    protected $route  = 'pengajuan.mutasi';
    protected $view   = 'pengajuan::mutasi.form';
    protected $perms  = 'resign_mutasi.mutasi';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view' => $this->view,
                'perms' => $this->perms,
                'title'  => 'Mutasi',
                'breadcrumb' => [
                    'Resign & Mutasi'         => url('pengajuan/tad/form'),
                    'Mutasi'  => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user = auth()->user();
        $so_id      = $user->position->org_struct_id ?? null;
        $records = Pengajuan::with('unitKerjaAsal', 'unitKerjaTujuan', 'pegawai', 'creator')
            ->when(
                !$user->cekDivisiHC(),
                function ($qqq) use ($so_id, $user) {
                    $qqq->where('unit_kerja_asal', $so_id);
			// ->orWhereHas(
                        // 'approvalsAll',
                        // function ($q) use ($user){
                        //    $q->where(
                        //        function ($qq) use ($user){
                        //            $qq->where('user_id', auth()->user()->id)
                        //            ->whereIn('group_id', auth()->user()->getGroupIds());
                        //        }
                        //    );
                        // }
                    // );
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
                    $q->where('unit_kerja_asal', $location_id)->orWhere('unit_kerja_tujuan', $location_id);
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
            ->orderBy('updated_at', 'DESC')
            ->get();

        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'no_tiket',
                function ($r) {
                    return "<span class=''>" . $r->no_tiket . "</span><br><span class=''>" . $r->tgl_pengajuan->format('d/m/Y') .  "</span>";
                }
            )
            ->editColumn(
                'unit_kerja_asal',
                function ($r) {
                    return '<span class="">' . ($r->unitKerjaAsal->name ?? '') . '</span>';
                }
            )
            ->editColumn(
                'unit_kerja_tujuan',
                function ($r) {
                    return '<span class="">' . ($r->unitKerjaTujuan->name ?? '') . '</span>';
                }
            )
            ->editColumn(
                'pegawai',
                function ($r) {
                    return $this->render(
                        'pengajuan::resign.partials.tad',
                        ['r' => $r]
                    );
                    // return $r->pegawai ? $r->pegawai->count() : '-';
                }
            )
            ->editColumn(
                'status',
                function ($record) {
                    return $record->labelStatus($record->status ?? 'new');
                    // if ($record->status === 'submit') {
                    //     return '<span class="badge badge-primary text-white">Waiting Approval</span></span>';
                    // } elseif ($record->status === 'draft') {
                    //     return '<span class="badge badge-warning text-white">Draft</span></span>';
                    // } elseif ($record->status === 'rejected') {
                    //     return '<span class="badge badge-danger text-white">Ditolak</span></span>';
                    // } elseif ($record->status === 'approved' || $record->status === 'completed') {
                    //     return '<span class="badge badge-success text-white">Completed</span></span>';
                    // }
                    // return $record->status;
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
                    if (in_array($r->status, [0, 'draft', 'rejected'])) {
                        $buttons[] = 'type:edit|page:true|id:' . $r->id;
                    }
                    if (auth()->user()->checkPerms($this->perms . '.approve') && in_array($r->status, ['1', 'submit', 'waiting.approval']) && $r->checkApproval()) {
                        $buttons[] = [
                            'type' => 'show',
                            'page' => true,
                            'id' => $r->id,
                            'label' => 'Approval',
                            'icon' => 'fa fa-check text-primary',
                            'url' => route($this->route . '.show', $r->id),
                        ];
                    }
                    if (auth()->user()->checkPerms($this->perms . '.delete') && in_array($r->status, ['draft', 'rejected'])) {
                        $buttons[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus No Tiket <b>' . $r->no_tiket . '?</b>"';
                    }
                    if ($r->status === 'completed' || $r->status == 'submit') {
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
                    return $this->makeDropdown($buttons);
                }
            )
            ->rawColumns(['no_tiket', 'unit_kerja_asal', 'unit_kerja_tujuan', 'status', 'action', 'created_at', 'tgl_pengajuan'])
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
                        $this->makeColumn('name:no_tiket|label:Pengajuan Mutasi|sortable:false|className:text-center'),
                        // $this->makeColumn('name:tgl_pengajuan|label:Tgl Pengajuan|sortable:false|className:text-center'),
                        $this->makeColumn('name:unit_kerja_asal|label:Unit Kerja Asal|sortable:false|className:text-center'),
                        $this->makeColumn('name:unit_kerja_tujuan|label:Unit Kerja Tujuan|sortable:false|className:text-center'),
                        $this->makeColumn('name:pegawai|label:Pegawai|sortable:false|className:text-center'),
                        $this->makeColumn('name:status|label:Status|sortable:false'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );
        return $this->render($this->view . '.index');
    }

    public function create(Tad $tad)
    {
        $POSISI_TAD = Jabatan::whereHas('personils')
            ->orderBy('NM_UNIT', 'ASC')
            ->get();
        $struct_tujuan = OrgStruct::groupByLevel([], true);
        $struct = OrgStruct::groupByLevel([], true);
        // dd(119, json_decode($struct), json_decode($struct_tujuan));
        return $this->render(
            $this->view . '.create',
            compact('POSISI_TAD', 'struct', 'struct_tujuan')
        );
    }

    public function store(Tad $tad, PengajuanMutasiRequest $request)
    {
        return Pengajuan::createByRequest($request);
    }

    public function show($id)
    {
        $record = Pengajuan::with('unitKerjaTujuan', 'cc', 'pegawai.jabatan')->find($id);
        return $this->render(
            $this->view . '.show',
            compact('record')
        );
    }

    public function edit($id)
    {
        $record = Pengajuan::with('unitKerjaTujuan', 'cc', 'pegawai.jabatan')->findOrFail($id);
        $POSISI_TAD = Jabatan::whereHas('personils')
            ->orderBy('NM_UNIT', 'ASC')
            ->get();
        $struct_tujuan = OrgStruct::groupByLevel([], true);
        $struct = OrgStruct::groupByLevel([], true);
        return $this->render(
            $this->view . '.edit',
            compact(
                'record',
                'POSISI_TAD',
                'struct',
                'struct_tujuan'
            )
        );
    }

    public function update(PengajuanMutasiRequest $request, $id)
    {
        $record = Pengajuan::find($id);
        return $record->updateByRequest($request);
    }

    public function destroy($id, Request $request)
    {
        $record = Pengajuan::find($id);
        return $record->deleteByRequest($request);
    }

    public function riwayat($id)
    {
        $record = Pengajuan::with('logs')->find($id);
        $this->setPrepare(
            [
                'title'  => 'Pengajuan Mutasi | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->no_tiket . '</span>',
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
        $record = Pengajuan::findOrFail($id);
        return $this->render($this->view . '.approval', compact('record'));
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
                'title'  => 'Pengajuan Mutasi | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->no_tiket . '</span>',
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

        $pdf = PDF::loadHTML($render)
            ->setPaper('a4')
            // ->setOptions(['isHtml5ParserEnabled' => true])
            ->setWarnings(false);

        return $pdf->stream('Pengajuan Mutasi TAD.pdf', 'Pengajuan Mutasi TAD');
    }
}
