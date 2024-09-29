<?php

namespace Modules\Pengajuan\Http\Controllers\Tad;

use App\Entities\EHC\Jabatan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\KategoriVendor;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\KepegawaianMigrasi;
use Modules\Pengajuan\Entities\Tad\Quota;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;
use Yajra\DataTables\DataTables;

class QuotaController extends Controller
{
    protected $module   = 'personil.quota';
    protected $route    = 'personil.quota';
    protected $view     = 'pengajuan::tad.quota';
    protected $perms    = 'personil.quota';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'title'  => 'Quota',
                'breadcrumb' => [
                    'Personil & Quota' => route($this->route . '.index'),
                    'Quota' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $records = QuotaPeriode::with(
            [
                'quotas' => function ($q) {
                    $q->select(
                        'pengajuan_tad_quota_periode_id',
                        // 'quota',
                        'org_struct_id',
                        'posisi_tad_id'
                    );
                }
            ]
        )
            ->orderBy('year', 'DESC')
            ->orderBy('semester', 'ASC')
            ->orderByRaw("CASE WHEN updated_at > created_at THEN updated_at ELSE created_at END DESC")
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike(['year', 'semester'], $keyword);
                }
            )
            ->when(
                $year = request()->year,
                function ($q) use ($year) {
                    $q->where('year', $year);
                }
            )
            ->when(
                $semester = request()->semester,
                function ($q) use ($semester) {
                    if ($semester === 'Satu') {
                        $q->where('semester', $semester);
                    } else if ($semester === 'Dua') {
                        $q->where('semester', $semester);
                    } else {
                    }
                }
            );
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'year',
                function ($r) {
                    return '<span class="badge badge-danger text-white">'. $r->year .'</span>';
                }
            )
            ->editColumn(
                'semester',
                function ($r) {
                    return '<span class="badge badge-primary text-white">'. $r->semester .'</span>';
                }
            )
            ->editColumn(
                'level',
                function ($r) {
                    return '<span class="badge badge-dark text-white">'. $r->level_show .'</span>';
                }
            )
            ->editColumn(
                'version',
                function ($r) {
                    return '<span style="padding: 3px 8px; border-radius: 6px; color: #ffffff; background-color: #c034eb;">'. $r->version .'</span>';
                }
            )
            ->editColumn(
                'jmlh_quota',
                function ($r) {
                    // return [$r->quota, $r->quotas()->sum('quota')];
                    // return number_format($r->quota, 0, ',', '.');
		    return '<span class="badge badge-warning text-white">'. number_format($r->quota, 0, ',', '.') .'</span>';
                }
            )
            ->editColumn(
                'jmlh_pemenuhan',
                function ($r) {
                    // return [$r->fulfillment, $r->quotas()->sum('fulfillment')];
                    // return number_format($r->fulfillment, 0, ',', '.');
                    return '<span style="padding: 3px 8px; border-radius: 6px; color: #ffffff; background-color: #34c6eb;">'. number_format($r->fulfillment, 0, ',', '.') .'</span>';
		}
            )
            ->editColumn(
                'status',
                function ($record) {
                    if ($record->status === 'submit') {
                        return '<span class="badge badge-primary text-white">Waiting Approval</span></span>';
                    } elseif ($record->status === 'completed') {
                        return '<span class="badge badge-success text-white">Completed</span></span>';
                    } elseif ($record->status === 'rejected') {
                        return '<span class="badge badge-danger text-white">Ditolak</span></span>';
                    } elseif ($record->status === 'draft') {
                        return '<span class="badge badge-warning text-white">Draft</span></span>';
                    } elseif ($record->status === 'new-position') {
                        return '<span class="badge badge-warning text-white">Posisi TAD Baru</span></span>';
                    } elseif ($record->status === 'new-struct') {
                        return '<span class="badge badge-warning text-white">Struktur Baru</span></span>';
                    } elseif ($record->status === 'waiting.approval.upgrade') {
                        return '<span class="badge badge-primary text-white">Waiting Approval Revisi</span></span>';
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
                'action',
                function ($r) {
                    $statusPersonil = false;
                    if (KepegawaianMigrasi::whereIn('status', [KepegawaianMigrasi::MIGRATE, KepegawaianMigrasi::WORK])->count() < 2) {
                        $statusPersonil = true;
                    }

                    $status = false;
                    if (
                        \Modules\Pengajuan\Entities\Tad\QuotaPeriode::where('status', 'completed')
                        ->orWhere('version', '>', 0)
                        ->get()
                        ->first()
                    ) {
                        $status = true;
                    }
                    $action = [];
                    $action[] = 'type:show|label:Detail|page:true|id:' . $r->id;
                    // return [$r->status, gettype($r->status)];
                    if ($statusPersonil) {
                        $action[] = [
                            'type'  => 'custom',
                            'class' => 'disableQuota',
                            'icon'  => 'fas fa-edit text-warning',
                            'label' => 'Ubah',
                            'page'  => false,
                            'url'   => 'javascript:quotaDisabled()',
                        ];
                        $action[] = [
                            'type'  => 'custom',
                            'class' => 'disableQuota',
                            'icon'  => 'fas fa-plus-circle text-info',
                            'label' => 'Ubah Quota',
                            'page'  => false,
                            'url'   => 'javascript:quotaDisabled()',
                        ];
                        $action[] = [
                            'type'  => 'custom',
                            'class' => 'disableQuota',
                            'icon'  => 'fas fa-trash text-danger',
                            'label' => 'Hapus',
                            'page'  => false,
                            'url'   => 'javascript:quotaDisabled()',
                        ];
                    } else {
                        if (in_array($r->status, ['draft', 'rejected']) && $r->version == 0 && auth()->user()->checkPerms($this->perms . '.edit')) {
                            $action[] = 'type:edit|class:base-modal--render|page:true|label:Ubah|id:' . $r->id;
                        }
                        if (in_array($r->status, ['draft', 'rejected', 'new-position', 'new-struct']) && auth()->user()->checkPerms($this->perms . '.edit')) {
                            $action[] = [
                                'type' => 'custom',
                                'icon' => 'fas fa-plus-circle text-info',
                                'label' => 'Ubah Quota',
                                'page' => true,
                                'url' => route($this->route . '.detail.index', $r->id)
                            ];
                        }
                        if ($r->canDelete()) {
                            $action[] = [
                                'type'  => 'delete',
                                'icon'  => 'fa fa-trash text-danger',
                                'label' => 'Hapus',
                                'attrs' => "Hapus Tahun " . $r->year . " Semester " . $r->semester . "?",
                                'id'    =>  $r->id,
                                'url'   =>  route($this->route . '.destroy', $r->id),
                            ];
                        }
                    }
                    // return [auth()->user()->checkPerms($this->perms . '.approve'), in_array($r->status, ['submit', 'waiting.approval.upgrade']), $r->checkApproval()];
                    if (auth()->user()->checkPerms($this->perms . '.approve') && in_array($r->status, ['submit', 'waiting.approval.upgrade']) && $r->checkApproval()) {
                        $action[] = [
                            'type' => 'show',
                            'page' => true,
                            'id' => $r->id,
                            'label' => 'Approval',
                            'icon' => 'fa fa-check text-primary',
                            'url' => route($this->route . '.approval', $r->id),
                        ];
                    }
                    if (in_array($r->status, ['approved', 'completed']) && auth()->user()->checkPerms($this->perms . '.edit')) {
                        $action[] = [
                            'type' => 'upgrade',
                            'icon' => 'fa fa-sync text-warning',
                            'label' => 'Revisi',
                            'url' => route($this->route . '.getUpgrade', $r->id),
                            'id'   => $r->id,
                            'attrs' => 'data-swal-ok="Upgrade" data-swal-text="Data yang telah di-upgrade akan dikembalikan untuk dapat diperbarui!"',
                        ];
                    }
                    if (!in_array($r->status, ['draft']) && auth()->user()->checkPerms($this->perms . '.view')) {
                        $action[] = [
                            'type' => 'show',
                            'icon' => 'fas fa-chart-line text-info',
                            'label' => 'Tracking Approval',
                            'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                            'url' => route($this->route . '.tracking', $r->id),
                            'id' => $r->id,
                        ];
                    }
                    $action[] = [
                        'type' => 'show',
                        'label' => 'Riwayat',
                        'icon' => 'fa flaticon2-time text-success',
                        'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                        'url' => route($this->route . '.riwayat', $r->id),
                        'id' => $r->id,
                    ];
                    return $this->makeDropdown($action);
                }
            )
            ->rawColumns(
                [
                    'year',
                    'semester',
                    'level',
                    'version',
                    'jmlh_quota',
                    'jmlh_pemenuhan',
                    'status', 'action', 'created_at'
                ]
            )
            ->make(true);
    }

    public function index()
    {
        $this->setPrepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:year|label:Tahun|sortable:false|className:text-center'),
                        $this->makeColumn('name:semester|label:Semester|sortable:false|className:text-center'),
                        $this->makeColumn('name:level|label:Struktur|sortable:false|className:text-center'),
                        $this->makeColumn('name:version|label:Versi|sortable:false|className:text-center'),
                        $this->makeColumn('name:jmlh_quota|label:Jumlah Quota|sortable:false|className:text-center'),
                        $this->makeColumn('name:jmlh_pemenuhan|label:Jumlah Pemenuhan|sortable:false|className:text-center'),
                        $this->makeColumn('name:status|label:Status|sortable:false|className:text-center'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:50px')
                    ]
                ],
            ]
        );
        return $this->render(
            $this->view . '.index',
        );
    }

    public function create()
    {
        return $this->render(
            $this->view . '.create',
        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'year'      => ['required', 'unique_with:trans_pengajuan_tad_quota_periode,year,semester,level'],
                'semester'  => ['required', 'unique_with:trans_pengajuan_tad_quota_periode,year,semester,level'],
                'level'     => ['required', 'unique_with:trans_pengajuan_tad_quota_periode,year,semester,level'],
            ]
        );
        return QuotaPeriode::createByRequest($request);
    }

    public function show($id)
    {
        $periode = QuotaPeriode::with(
            [
                'logs' => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ]
        )->find($id);
        $subtitle = 'Tahun ' . $periode->year . ' Semester ' . $periode->semester;
        $ORG_STRUCT = OrgStruct::groupByLevel([$periode->level]);
        $KATEGORI_VENDOR = KategoriVendor::with(
            [
                'jabatanTad' => function ($q) {
                    $q->orderBy('NM_UNIT', 'ASC');
                }
            ]
        )->whereHas('jabatanTad')->get();
        $tableStruct = [
            $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
            $this->makeColumn('name:so.name|label:Struktur Organisasi|sortable:false|className:text-left'),
            $this->makeColumn('name:posisi.NM_UNIT|label:Posisi TAD|sortable:false|className:text-left'),
            $this->makeColumn('name:quota|label:Quota|sortable:false|className:text-center'),
            // $this->makeColumn('name:deskripsi|label:Deskripsi|sortable:false|className:text-left|width:400px'),
            $this->makeColumn('name:created_at|label:Diperbarui|sortable:false'),
        ];
        $this->setPrepare(
            [
                'title'  => 'Quota TAD',
                'breadcrumb' => [
                    'Pengajuan'   => url('pengajuan/tad/form'),
                    'Quota TAD'   => route($this->route . '.index'),
                    $subtitle     => route($this->route . '.show', $id),
                ],
                'tableStruct' => [
                    'url' => route($this->route . '.detail.grid', $id),
                    'datatable_1' => $tableStruct
                ],
            ]
        );
        // dd(json_decode($periode));
        return $this->render(
            $this->view . '.show',
            compact('periode', 'ORG_STRUCT', 'KATEGORI_VENDOR')
        );
    }

    public function edit($id)
    {
        $record = QuotaPeriode::find($id);
        return $this->render(
            $this->view . '.edit',
            [
                'record' => $record
            ]
        );
    }

    public function update(Request $request, $id)
    {
        if ($request->detail == '1') {
            $request->validate(
                [
                    'quota'             => ['required'],
                    'quota.*'           => ['required', 'min:0'],
                    'quota.*.*'         => ['required', 'min:0'],
                    'quota.*.*.quota'   => ['required', 'min:0'],
                ]
            );
        } else {
            $request->validate([
                'year'     => ['required', 'unique_with:trans_pengajuan_tad_quota_periode,year,semester,level,id,' . $id],
                'semester' => ['required', 'unique_with:trans_pengajuan_tad_quota_periode,year,semester,level,id,' . $id],
            ]);
        }
        // dd($request->all());
        $record = QuotaPeriode::findOrFail($id);
        return $record->updateByRequest($request);
    }

    public function destroy($id, Request $request)
    {
        $record = QuotaPeriode::find($id);
        return $record->deleteByRequest($request);
    }

    public function countAvailable(Request $request)
    {
        $available =  QuotaPeriode::countAvailable(
            $request->year ?? 2022,
            $request->semester ?? 'Satu',
            $request->level ?? 'capem',
            $request->posisi_tad_id ?? 1
        );
        return response()->json(
            [
                'status' => true,
                'data'  => $available,
            ]
        );
    }

    public function detail($id)
    {
        $periode = QuotaPeriode::findOrFail($id);
        $subtitle = 'Tahun ' . $periode->year . ' Semester ' . $periode->semester;
        $ORG_STRUCT = OrgStruct::groupByLevel([$periode->level]);
        $KATEGORI_VENDOR = KategoriVendor::with(
            [
                'jabatanTad' => function ($q) {
                    $q->orderBy('NM_UNIT', 'ASC');
                }
            ]
        )
            ->whereHas('jabatanTad')
            ->get();

        $this->setPrepare(
            [
                'title'  => 'Quota TAD',
                'breadcrumb' => [
                    'Pengajuan'   => url('pengajuan/tad/form'),
                    'Quota TAD'   => route($this->route . '.index'),
                    $subtitle     => route($this->route . '.show', $id),
                ],
            ]
        );

        $this->route    = 'personil.quota.detail.create';
        return $this->render(
            $this->view . '.detail',
            compact('periode', 'ORG_STRUCT', 'KATEGORI_VENDOR')
        );
    }
    public function gridQuota($id)
    {
        $records = Quota::with('so', 'posisi.kategori')
            ->where('pengajuan_tad_quota_periode_id', $id)
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    // $q->whereLike(['year', 'semester', 'quota', 'description'], $keyword);
                }
            );
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'so.name',
                function ($r) {
                    return '<div>
                    <span><span class="text-bold">Level : </span>' . ($r->so->show_level) . '</span><br>
                    <span><span class="text-bold">Nama : </span>' . $r->so->name . '</span>
                </div>';
                }
            )
            ->editColumn(
                'posisi.NM_UNIT',
                function ($r) {
                    return '<div>
                    <span><span class="text-bold">Kategori : </span>' . ($r->posisi->kategori->nama) . '</span><br>
                    <span><span class="text-bold">Posisi : </span>' . $r->posisi->NM_UNIT . '</span>
                </div>';
                }
            )
            ->editColumn(
                'status',
                function ($record) {
                    if ($record->status == 1) {
                        return '<span class="badge badge-primary text-white">Waiting Approval</span></span>';
                    } elseif ($record->status === 'completed') {
                        return '<span class="badge badge-success text-white">Completed</span></span>';
                    } elseif ($record->status === 'rejected') {
                        return '<span class="badge badge-danger text-white">Ditolak</span></span>';
                    } elseif ($record->status === 0 || $record->status == 'draft') {
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
                'action',
                function ($r) {
                    $action = [
                        [ // 'type:show|class:base-modal--render|page:true|label:Detail|id:' . $r->id,
                            'type'  =>  'custom',
                            'icon'  =>  'fa fa-edit text-warning',
                            'class' =>  'base-modal--render',
                            'page'  =>  'true',
                            'label' =>  'Ubah Quota',
                            'id'    =>  $r->id,
                            'url'   =>  route($this->route . '.detail.edit-quota', $r->id),
                        ]
                    ];
                    if ($r->canDelete()) {
                        $action[] = [
                            'type'  => 'delete',
                            'icon'  => 'fa fa-trash text-danger',
                            'label' => 'Hapus',
                            'attrs' => 'data-confirm-text="Hapus ' . $r->so->name . ' ' . $r->posisi->NM_UNIT . '?"',
                            'id'    =>  $r->id,
                            'url'   =>  route($this->route . '.detail.delete-quota', $r->id),
                        ];
                    }
                    return $this->makeDropdown($action);
                }
            )
            ->rawColumns(['so.name', 'posisi.NM_UNIT', 'status', 'action', 'created_at'])
            ->make(true);
    }

    public function approval($id)
    {
        $periode = QuotaPeriode::with(
            [
                'logs' => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ]
        )->find($id);
        $subtitle = 'Tahun ' . $periode->year . ' Semester ' . $periode->semester;
        $ORG_STRUCT = OrgStruct::groupByLevel([$periode->level]);
        $KATEGORI_VENDOR = KategoriVendor::with(
            [
                'jabatanTad' => function ($q) {
                    $q->orderBy('NM_UNIT', 'ASC');
                }
            ]
        )->whereHas('jabatanTad')->get();
        $tableStruct = [
            $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
            $this->makeColumn('name:so.name|label:Struktur Organisasi|sortable:false|className:text-left'),
            $this->makeColumn('name:posisi.NM_UNIT|label:Posisi TAD|sortable:false|className:text-left'),
            $this->makeColumn('name:quota|label:Quota|sortable:false|className:text-center'),
            // $this->makeColumn('name:deskripsi|label:Deskripsi|sortable:false|className:text-left|width:400px'),
            $this->makeColumn('name:created_at|label:Diperbarui|sortable:false'),
        ];
        $this->setPrepare(
            [
                'title'  => 'Quota TAD',
                'breadcrumb' => [
                    'Pengajuan'   => url('pengajuan/tad/form'),
                    'Quota TAD'   => route($this->route . '.index'),
                    $subtitle     => route($this->route . '.show', $id),
                ],
                'tableStruct' => [
                    'url' => route($this->route . '.detail.grid', $id),
                    'datatable_1' => $tableStruct
                ],
            ]
        );
        return $this->render(
            $this->view . '.approval',
            compact('periode', 'ORG_STRUCT', 'KATEGORI_VENDOR')
        );
    }

    public function approvalSave(Request $request, $id)
    {
        auth()->user()->storeLog($this->module, 'approval');
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
        $record = QuotaPeriode::findOrFail($id);
        return $record->handleApproveSave($request);
    }

    public function getUpgrade($id)
    {
        $record = QuotaPeriode::with(
            [
                'logs' => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ]
        )->find($id);
        return $this->render(
            $this->view . '.upgrade',
            compact('record')
        );
    }

    public function saveUpgrade(Request $request, $id)
    {
        $record = QuotaPeriode::with(
            [
                'logs' => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ]
        )->find($id);
        return $record->handleUpgrade($request);
    }

    public function riwayat($id)
    {
        $periode = QuotaPeriode::with(
            [
                'logs' => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ]
        )->find($id);
        $subtitle = 'Tahun ' . $periode->year . ' Semester ' . $periode->semester;
        $this->setPrepare(
            [
                'title'  => 'Quota TAD',
                'breadcrumb' => [
                    'Pengajuan'   => url('pengajuan/tad/form'),
                    'Quota TAD'   => route($this->route . '.index'),
                    $subtitle     => route($this->route . '.show', $id),
                ],
            ]
        );
        $show_title = true;
        return $this->render(
            $this->view . '.riwayat',
            compact('periode', 'show_title')
        );
    }

    public function tracking($id)
    {
        $periode = QuotaPeriode::find($id);
        $record = QuotaPeriode::find($id);
        $subtitle = 'Tahun ' . $periode->year . ' Semester ' . $periode->semester;
        $this->setPrepare(
            [
                'title'  => 'Quota TAD | <span class="label label-xl label-danger label-inline text-nowrap">' . $subtitle . '</span>',
            ]
        );
        $show_title = true;
        return $this->render(
            $this->view . '.tracking',
            compact('periode', 'show_title', 'record')
        );
    }
}
