<?php

namespace Modules\Pengajuan\Http\Controllers\Tad;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Master\Entities\KategoriVendor;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Vendor;
use Modules\Master\Entities\Wawancara\Kompetensi;
use Modules\Pengajuan\Entities\Tad\CC;
use Modules\Pengajuan\Entities\Tad\DetailKandidat;
use Modules\Pengajuan\Entities\Tad\Kandidat;
use Modules\Pengajuan\Entities\Tad\Pengajuan;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;
use Modules\Pengajuan\Entities\Tad\Requirement;
use Modules\Pengajuan\Entities\Tad\Wawancara\Wawancara;
use Modules\Pengajuan\Http\Requests\CcRequest;
use Modules\Pengajuan\Http\Requests\PengajuanTadRequest;
use Modules\Pengajuan\Http\Requests\PengajuanTadUpdateRequest;
use Modules\Pengajuan\Jobs\SendMailJob;
use Yajra\DataTables\DataTables;

class PengajuanController extends Controller
{
    protected $module   = 'pengajuan.tad.form';
    protected $route    = 'pengajuan.pengajuan';
    protected $view     = 'pengajuan::tad.form';
    protected $perms    = 'pengajuan.tad.form';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view' => $this->view,
                'perms' => $this->perms,
                'title'  => 'Pengajuan TAD',
                'breadcrumb' => [
                    // 'Pengajuan'     => route($this->route . '.index'),
                    // 'Pengajuan TAD' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        // return 123;
        $user       = auth()->user();
        $user_id    = $user->id;
        $so_id      = $user->position->org_struct_id ?? null;
        // dd(54, $user->isEhc, $so_id);
        $records = Pengajuan::with('requirement', 'approvals')
            ->when(
                $user->isEhc && !$user->cekDivisiHC() && ($so_id),
                function ($q) use ($so_id, $user) {
                    $q
                        ->where('so_id', $so_id);
                        // ->orWhereHas(
                        //    'approvalsAll',
                        //    function ($q) use ($user) {
                        //        $q->where(
                        //            function ($q) use ($user) {
                        //                $q->where('user_id', auth()->user()->id)
                        //                    ->whereIn('group_id', auth()->user()->getGroupIds());
                        //           }
                        //       );
                        //   }
                        // );
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
                    $q->where('so_id', $location_id);
                }
            )
            ->when(
                $date_start = request()->date_start,
                function ($q) use ($date_start) {
                    $q->where('tgl_pengajuan', '>=', Carbon::createFromFormat('d/m/Y', $date_start)->format('Y-m-d'));
                }
            )
            ->when(
                $date_end = request()->date_end,
                function ($q) use ($date_end) {
                    $q->where('tgl_pengajuan', '<=', Carbon::createFromFormat('d/m/Y', $date_end)->format('Y-m-d'));
                }
            )
            ->when(
                $user->isVendor,
                function ($q) use ($user) {
                    $q->whereIn('status', ['approved', 'rejected', 'completed', 'waiting.approval']);
                    $q->whereHas(
                        'requirement',
                        function ($q) use ($user) {
                            $q->where('vendor_id', $user->vendor_id);
                        }
                    );
                }
            )
	    ->orderBy('updated_at', 'DESC');

        return DataTables::of($records)
            ->order(
                function ($q) {
                    $q->orderBy('created_at', 'desc');
                }
            )
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'no_tiket',
                function ($r) {
                    return "<span>" . $r->no_tiket . "</span><br>"
                        . "<span>" . $r->tgl_pengajuan->translatedFormat('d/m/Y') .  "</span>";
                }
            )
            ->editColumn(
                'kandidat',
                function ($r) {
                    $kandidat = DetailKandidat::with('summary')
                        ->whereHas(
                            'summary',
                            function ($query) use ($r) {
                                $query->where('pengajuan_id', $r->id);
                            }
                        )->get();
                    return '<div>
                        <span><span class="text-bold">Permintaan : </span>' . $r->requirement()->get()->sum('jumlah') . '</span><br>
                        <span><span class="text-bold" >Kandidat : </span>' . $kandidat->count() . '</span>
                    </div>';
                }
            )
            ->editColumn(
                'tanggal_pengajuan',
                function ($r) {
                    return '<div>
                        <span>' . ($r->so->name ?? '') . '</span><br>
                        <span>Tahun ' . $r->year . ' Semester ' . $r->semester . '</span>
                    </div>';
                }
            )
            ->editColumn(
                'status',
                function ($record) use ($user) {
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
                function ($r) use ($so_id) {
                    $user = auth()->user();
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

                    if ($user->checkPerms($this->perms . '.delete') && in_array($r->status, ['0', 'draft', 'rejected'])) {
                        $buttons[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus No Tiket <b>' . $r->no_tiket . '?</b>"';
                    }

                    if ($r->status == 'completed' || $r->status == 'waiting.approval') {
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
            ->rawColumns(['action', 'created_at', 'status', 'tanggal_pengajuan', 'kandidat', 'no_tiket'])
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
                        $this->makeColumn('name:no_tiket|label:Nomor Tiket|sortable:false|className:text-center'),
                        $this->makeColumn('name:tanggal_pengajuan|label:Pengajuan|sortable:false|className:text-center'),
                        $this->makeColumn('name:kandidat|label:Kandidat|sortable:false|className:text-center'),
                        $this->makeColumn('name:status|label:Status|sortable:false'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width: 120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );
        return $this->render($this->view . '.index');
    }

    public function create()
    {
        $KATEGORI_VENDOR = KategoriVendor::whereHas('jabatanTad')->orderBy('nama', 'ASC')->get();
        $VENDOR = Vendor::whereHas('tad')->orderBy('nama', 'ASC')->get();
        // dd(json_decode($KATEGORI_VENDOR));
        $QUOTA_PERIODE = QuotaPeriode::orderBy('year', 'ASC')->get();
        return $this->render(
            $this->view . '.create',
            compact('KATEGORI_VENDOR', 'QUOTA_PERIODE', 'VENDOR')
        );
    }

    public function store(PengajuanTadRequest $request)
    {
        return Pengajuan::createByRequest($request);
    }

    public function show($id)
    {
        $record = Pengajuan::findOrFail($id);

        $grid = [
            $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
            $this->makeColumn('name:kategori|label:Kategori|sortable:false'),
            $this->makeColumn('name:jabatan|label:Posisi TAD|sortable:false'),
            $this->makeColumn('name:jenis_kelamin|label:Jenis Kelamin|sortable:false'),
            $this->makeColumn('name:jumlah|label:Jumlah|sortable:false'),
            $this->makeColumn('name:kandidat|label:Kandidat|sortable:false|className:text-left'),
            $this->makeColumn('name:vendor|label:Vendor|sortable:false'),
        ];
        if ($record->status == 'vendor.submit') {
            $grid[] = $this->makeColumn('name:tindak_lanjut|label:Status|sortable:false');
        }
        // $grid[] = $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px');
        if (($record->active_logs->status ?? '') == 'closed') {
            $grid = [
                $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                $this->makeColumn('name:kategori|label:Kategori|sortable:false'),
                $this->makeColumn('name:jabatan|label:Posisi TAD|sortable:false'),
                $this->makeColumn('name:jenis_kelamin|label:Jenis Kelamin|sortable:false'),
                $this->makeColumn('name:jumlah|label:Jumlah|sortable:false'),
                $this->makeColumn('name:tad|label:TAD|sortable:false|className:text-left'),
                $this->makeColumn('name:vendor|label:Vendor|sortable:false'),
                $this->makeColumn('name:status|label:Status|sortable:false|className:text-center'),
                // $this->makeColumn('name:action|label:Opsi|sortable:false'),
            ];
        }

        $this->setPrepare(
            [
                'tableStruct' => [
                    'url' => route($this->route . '.requirement.grid', $record),
                    'datatable_1' => $grid
                ],
            ]
        );
        return $this->render(
            $this->view . '.show',
            compact('record')
        );
    }

    public function edit($id)
    {
        $form = Pengajuan::findOrFail($id);
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Pengajuan TAD',
                'tableStruct2' => [
                    'url' => route($this->route . '.cc.grid', $form->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:user|label:Nama|sortable:false'),
                        $this->makeColumn('name:jabatan|label:Jabatan|sortable:false'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px'),
                    ],
                ]
            ]
        );
        $KATEGORI_VENDOR = KategoriVendor::whereHas('jabatanTad')->orderBy('nama', 'ASC')->get();
        $VENDOR = Vendor::whereHas('tad')->orderBy('nama', 'ASC')->get();
        $QUOTA_PERIODE = QuotaPeriode::orderBy('year', 'ASC')->get();

        return $this->render(
            $this->view . '.edit',
            [
                'edit'              => $form,
                'KATEGORI_VENDOR'   => $KATEGORI_VENDOR,
                'VENDOR'            => $VENDOR,
                'QUOTA_PERIODE'     => $QUOTA_PERIODE
            ]
        );
    }

    public function update(Pengajuan $pengajuan, PengajuanTadUpdateRequest $request)
    {
        return $pengajuan->updateByRequest($request);
    }

    public function destroy(Pengajuan $pengajuan, Request $request)
    {
        return $pengajuan->deleteByRequest($request);
    }

    public function requirementGrid($id)
    {
        $user = auth()->user();
        $pengajuan = Pengajuan::findOrFail($id);
        $records = $pengajuan->requirement();
        if ($user->isVendor) {
            $records->where('vendor_id', $user->vendor_id);
        }
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'kategori',
                function ($r) {
                    return '<span>' . $r->jabatan->kategori->nama ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'jabatan',
                function ($r) {
                    return '<span>' . $r->jabatan->NM_UNIT ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'jenis_kelamin',
                function ($r) {
                    return "<span>" . $r->jenis_kelamin ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'jumlah',
                function ($r) {
                    return "<span>" . $r->jumlah ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'tad',
                function ($r) {
                    $kandidat = '<ul class="mb-0">';
                    if ($r->kandidats()->exists()) {
                        foreach ($r->kandidats()->where('accepted', 1)->get() as $item) {
                            $kandidat .= '<li>' . $item->tad->nama . '</li>';
                        }
                    } else {
                        return $kandidat .= '<li>-</li>';
                    }
                    $kandidat .= '</ul>';
                    return $kandidat;
                }
            )
            ->editColumn(
                'kandidat',
                function ($r) {
                    return $this->render('pengajuan::tad.partials.grid.requirement.kandidat', ['r' => $r]);
                }
            )
            ->editColumn(
                'vendor',
                function ($r) {
                    return "<span>" . $r->vendor->nama . "</span>";
                }
            )
            ->editColumn(
                'tindak_lanjut',
                function ($r) {
                    $penentuan     = $r->kandidat()->wherePivotNotNull('accepted')->count();
                    $kandidatTotal = $r->kandidat()->count();
                    if ($penentuan < $kandidatTotal) {
                        return '<em>Seleksi</em>';
                    }
                    if ($r->kandidat()->count() == $r->kandidat()->wherePivot('accepted', 1)->count()) {
                        return '<em>Lengkap</em>';
                    } else {
                        return '<em>Belum Lengkap</em>';
                    }
                }
            )
            // ->editColumn(
            //     'action',
            //     function ($r) use ($user) {
            //         $buttons = [];
            //         // if ($r->vendor_id == auth()->user()->vendor_id && !in_array($r->pengajuan->status, ['vendor.submit', 'hc.verified']) && $r->flag != 'process') {
            //         //     $buttons[] = 'type:custom|label:Isi Kandidat|icon:fas fa-users text-primary|url:kandidat/' . $r->id . '|attrs:data-modal-size="modal-lg"';
            //         // }
            //         // if ($r->flag == 'process' && (($user->isEhc && $user->checkPerms($this->perms . '.approve')) || ($user->isVendor && $user->vendor_id == $r->vendor_id))) {
            //         //     $buttons[] = 'type:custom|label:Kandidat|icon:fas fa-users text-primary|url:kandidat/' . $r->id . '|attrs:data-modal-size="modal-xl"';
            //         // }
            //         return $this->makeDropdown($buttons);
            //     }
            // )
            ->rawColumns(['tad', 'tindak_lanjut', 'kategori', 'jabatan', 'jenis_kelamin', 'jumlah', 'vendor'])
            ->make(true);
    }
    public function requirementDelete(Requirement $requirement)
    {
        return $requirement->deleteByRequest();
    }

    public function riwayat($id)
    {
        $record = Pengajuan::with(
            [
                'logs' => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ]
        )->find($id);
        $this->setPrepare(
            [
                'title'  => 'Pengajuan TAD | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->no_tiket . '</span>',
            ]
        );
        $show_title = true;
        return $this->render(
            $this->view . '.riwayat',
            compact('record', 'show_title')
        );
    }

    public function tracking($id)
    {
        $record = Pengajuan::find($id);
        $this->setPrepare(
            [
                'title'  => 'Pengajuan TAD | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->no_tiket . '</span>',
            ]
        );
        $show_title = true;
        return $this->render(
            $this->view . '.tracking',
            compact('show_title', 'record')
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

    public function kandidat(Requirement $requirement)
    {
        $datatable_2 = [
            $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
        ];
        if ($requirement->flag == 'open') {
            $datatable_2[] = $this->makeColumn('name:check|label:Pilih|sortable:false|width:20px|className:text-center');
        }
        $datatable_2[] = $this->makeColumn('name:nama|label:Nama Kandidat|sortable:false');
        $datatable_2[] = $this->makeColumn('name:status_kawin|label:Status Pernikahan|sortable:false');
        if ($requirement->flag == 'process') {
            $datatable_2[] = $this->makeColumn('name:status_wawancara|label:Status Wawancara|sortable:false|width:200px');
        }
        if ($requirement->flag == 'process') {
            $datatable_2[] = $this->makeColumn('name:status_penerimaan|label:Penerimaan|sortable:false|width:200px');
            $datatable_2[] = $this->makeColumn('name:action|label:Opsi|sortable:false');
        }
        $this->setPrepare(
            [
                'tableStruct' => [
                    'url' => route($this->route . '.kandidat.grid', $requirement->id),
                    'datatable_2' => $datatable_2
                ],
            ]
        );
        return $this->render(
            $this->view . '.kandidat.index',
            [
                'record' => $requirement
            ]
        );
    }
    public function kandidatGrid($id)
    {
        $user = auth()->user();
        $requirement = Requirement::with('pengajuan', 'kandidat')->find($id);
        $records = [];
        if ($requirement->flag == 'open') {
            $records = Tad::with(
                [
                    'kepegawaian',
                    'lastEmployment',
                    'pivotPengajuan' => function ($q) use ($requirement) {
                        // $q->where('id', $requirement->pengajuan_id);
                    }
                ]
            )->where(
                function ($w) use ($requirement, $user) {
                    $w
                        ->where('vendor_id',      $user->vendor_id)
                        ->where('jabatan_id',     $requirement->jabatan_id)
                        ->where('jenis_kelamin',  $requirement->jenis_kelamin)
                        // ->whereHas('keluarga')
                        ->where(
                            function ($q) {
                                $q
                                    ->whereDoesntHave('kepegawaian')
                                    ->orWhereHas(
                                        'lastEmployment',
                                        function ($q) {
                                            $q->whereIn(
                                                'status',
                                                [Kepegawaian::RESIGN, Kepegawaian::END]
                                            );
                                        }
                                    );
                            }
                        )
                        ->where(
                            function ($q) use ($requirement) {
                                $q->whereDoesntHave('pivotPengajuan')
                                    ->orWhereHas(
                                        'pivotPengajuan',
                                        function ($p) use ($requirement) {
                                            $p->where('id', $requirement->pengajuan_id);
                                        }
                                    );
                            }
                        );
                }
            )
                ->get();
            // ->filter(
            //     function ($r) use ($requirement) {
            //         if (isset($r->pivotPengajuan)) {
            //             if ($r->pivotPengajuan->where('id', $requirement->pengajuan_id)->count()) {
            //                 return true;
            //             } elseif ($r->pivotPengajuan->where('id', '!=', $requirement->pengajuan_id)->count()) {
            //                 return false;
            //             }
            //         } else {
            //             return true;
            //         }
            //         if (isset($r->lastEmployment)) {
            //             if ($r->lastEmployment->status) {
            //             }
            //         }
            //         return false;
            //     }
            // );
            // dd(579, json_decode($records), $requirement->pengajuan_id);
        } else {
            // return 565;
            $records = $requirement->kandidat;
        }
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'check',
                function ($r) use ($requirement) {
                    $checked = (Kandidat::where(['tad_id' => $r->id, 'requirement_id' => $requirement->id])->exists() ? 'checked' : '');
                    return '<label class="checkbox">
                        <input type="checkbox" ' . $checked . ' class="kandidat" data-max="' . $requirement->jumlah . '" name="kandidat_id[]" value="' . $r->id . '">
                        <span></span>
                    </label>';
                }
            )
            ->editColumn(
                'nama',
                function ($r) {
                    return $this->render('pengajuan::tad.personil.partials.grid.nama', ['r' => $r]);
                }
            )
            ->editColumn(
                'status_kawin',
                function ($r) {
                    return $r->status_perkawinan;
                }
            )
            ->editColumn(
                'status_wawancara',
                function ($r) {
                    if (isset($r->pivot->id)) {
                        $wawancara = Wawancara::ongoing($r->pivot->id)->first();
                        return !is_null($wawancara)
                            ? $wawancara->kesimpulanRaw()
                            : '<span>Belum Di Wawancara</span>';
                    }
                }
            )
            ->editColumn(
                'status_penerimaan',
                function ($r) {
                    if (isset($r->pivot->accepted)) {
                        switch ($r->pivot->accepted) {
                            case 1:
                                return '<span class="badge badge-success" >Diterima</span>';
                            case 2:
                                return '<span class="badge badge-danger" >Ditolak</span>';
                            default:
                                return '<span class="badge badge-light">Penentuan</span>';
                        }
                    }
                }
            )
            ->editColumn(
                'action',
                function ($r) use ($requirement, $user) {
                    // return $r->pivot->accepted;
                    // return [$r->id, $requirement->id, $requirement->flag];
                    $wawancara = null;
                    $buttons = [];
                    $route_param = [
                        'kandidat'      => $r->pivot->id ?? null,
                        'kandidat_id'   => $r->pivot->id ?? null,
                        'personil'      => $r->id,
                        'personil_id'   => $r->id,
                        'pengajuan_id'  => $requirement->pengajuan->id,
                        'requirement_id' => $requirement->id,
                        'should_redirect' => 'O'
                    ];
                    $buttons[] = [
                        'type'      => 'show',
                        'label'     => 'Detail',
                        'page'      => true,
                        'id'        => $r->id,
                        'url'       => route('pengajuan.show', isset($r->pivot->id) ? $r->id : $route_param),
                        // 'attrs' => 'target="_blank"'
                    ];
                    if (isset($r->pivot->id)) {
                        $wawancara = Wawancara::ongoing($r->pivot->id)->first();
                        if ($user->isEhc && $requirement->flag == 'process' && is_null($wawancara)) {
                            $buttons[] = [
                                'type'  => 'edit',
                                'label' => 'Form Wawancara',
                                'icon'  => 'fas fa-tasks text-primary mr-2',
                                // 'class' => 'base-modal--confirm',
                                'attrs' => 'onclick="$(\'.modal-backdrop.fade.show, .tooltip.fade.bs-tooltip-top.show\').remove();"',
                                'id'    => $r->pivot->id,
                                'url'   => route('pengajuan.wawancara.create', $r->pivot->id),
                            ];

                            $buttons[] = [
                                'type'   => 'custom',
                                'label'  => 'Undang Interview via WA',
                                'icon'   => 'fab fa-whatsapp text-success',
                                'page' => true,
                                'attrs' => 'target="_blank"',
                                'url' => 'https://wa.me/' . $r->telepon . '?text=Undangan%20Interview'
                                // 'url' => route($this->route. '.kandidat.penerimaan.create',$r->id),
                            ];
                        }
                        if ($r->pivot->accepted == null) {
                            $buttons[] = [
                                'type'  => 'edit',
                                'label' => 'Penerimaan',
                                'icon'  => 'fas fa-check-circle text-success mr-2',
                                // 'class' => 'base-modal--confirm',
                                'attrs' => 'onclick="$(\'.modal-backdrop.fade.show, .tooltip.fade.bs-tooltip-top.show\').remove();"',
                                'id'    => $r->pivot->id,
                                'url'   => route('pengajuan.kandidat.penerimaan.create', $route_param),
                            ];
                        } else if ($r->pivot->accepted == 2) {
                            $buttons[] = [
                                'type'  => 'edit',
                                'label' => 'Ubah Kandidat',
                                'icon'  => 'fas fa-edit text-success mr-2',
                                // 'class' => 'base-modal--confirm',
                                'attrs' => 'onclick="$(\'.modal-backdrop.fade.show, .tooltip.fade.bs-tooltip-top.show\').remove();"',
                                'id'    => $r->pivot->id,
                                'url'   => route('pengajuan.kandidat.edit', $route_param),
                            ];
                        }
                    }
                    // return $buttons;
                    return $this->makeDropdown($buttons);
                }
            )
            ->rawColumns(['check', 'nama', 'status_wawancara', 'status_penerimaan', 'action'])
            ->make(true);
    }
    public function kandidatStore(Requirement $requirement, Request $request)
    {
        // dd(527, $request->all());
        $request->validate(
            [
                // 'kandidat_id' => ['required','exists:ref_tad,id'],
                // 'kandidat_id.*' => ['required','exists:ref_tad,id']
            ]
        );
        $request->request->add(
            [
                'jumlah_requirement' => $requirement->jumlah
            ]
        );
        return $requirement->kandidatByRequest($request);
    }
    public function kandidatSend(Pengajuan $otorisasi_vendor, Request $request)
    {
        $request->request->add(
            [
                'jumlah_requirement' => $otorisasi_vendor
                    ->requirement()
                    ->where(
                        'vendor_id',
                        auth()->user()->vendor_id
                    )->sum('jumlah')
            ]
        );
        return $otorisasi_vendor->vendorByRequest($request);
    }

    public function sendInterviewMail($id)
    {
        $pengajuan = Pengajuan::with('kandidat')->find($id);
        // dd(642, json_decode($pengajuan));
        foreach ($pengajuan->kandidat as $key => $item) {
            dispatch(new SendMailJob($item->tad));
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Undangan Interview Terkirim',
                'data'    => $this
            ]
        );
    }
    public function sendInterviewMailPersonal($id)
    {
        $tad = Tad::find($id);
        dispatch(new SendMailJob($tad));
        return response()->json(
            [
                'success' => true,
                'message' => 'Undangan Interview Terkirim ke <b>' . $tad->nama . '</b>',
                'data'    => $this
            ]
        );
    }

    public function print($id)
    {
        $pengajuan = Pengajuan::with('requirement', 'requirement.kandidat', 'requirement.kandidats.employment')->findOrFail($id);

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
        return $pdf->stream('Pengajuan.pdf', 'Pengajuan');
    }
}
