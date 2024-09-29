<?php

namespace Modules\Pengajuan\Http\Controllers\Tad;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Entities\Tad\DetailKandidat;
use Modules\Pengajuan\Entities\Tad\Requirement;
use Modules\Pengajuan\Entities\Tad\SummaryKandidat;
use Yajra\DataTables\DataTables;

class PengajuanKandidatController extends Controller
{
    protected $module   = 'pengajuan.tad.kandidat';
    protected $route    = 'pengajuan.kandidat';
    protected $view     = 'pengajuan::tad.kandidat';
    protected $perms    = 'pengajuan.tad.kandidat';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view' => $this->view,
                'perms' => $this->perms,
                'title'  => 'Kandidat',
                'breadcrumb' => [
                    'Pengajuan'     => url('pengajuan/tad/kandidat'),
                    'Kandidat' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user       = auth()->user();
        $user_id    = $user->id;
        $so_id      = $user->position->org_struct_id ?? null;
        $records = Requirement::with('pengajuan')
            ->whereHas('pengajuan', function ($qqqq) use ($so_id, $user, $user_id) {
                $qqqq->where('status', 'completed')
                    ->when(
                        $user->isEhc && !$user->cekDivisiHC() && ($so_id),
                        function ($qqq) use ($so_id, $user) {
                            $qqq->where('so_id', $so_id);
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
                            $q->whereIn('status', ['approved', 'vendor.submit', 'hc.verified', 'completed'])->where('vendor_id', $user->vendor_id);
                        }
                    )
		    ->when(
                	$vendor_id = request()->vendor_id,
                	function ($q) use ($vendor_id) {
                    	    $q->where('vendor_id', $vendor_id);
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
            })->orderBy('created_at', 'DESC');

        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'kandidat',
                function ($r) {
                    $kandidat = DetailKandidat::with('summary')
                        ->whereHas('summary', function ($query) use ($r) {
                            $query->where('requirement_id', $r->id);
                        })->get();
                    return '<div>
                            <span class="text-bold">Permintaan : </span>' . $r->jumlah . '</span><br>
                            <span class="text-bold">Kandidat : </span>' . $kandidat->count() . '</span>
                        </div>';
                }
            )
            ->editColumn(
                'pengajuan',
                function ($r) {
                    return "<span>" . $r->pengajuan->no_tiket . "</span><br>" . "<span>" . $r->pengajuan->tgl_pengajuan->translatedFormat('d/m/Y') . "</span>";
                }
            )
            ->editColumn(
                'vendor',
                function ($r) {
                    return "<span>" . $r->vendor->nama . "</span><br>" . "<span>" .  $r->jabatan->NM_UNIT . "</span>";
                }
            )
            ->editColumn(
                'tanggal_pengajuan',
                function ($r) {
                    return '<div>
                            <span>' . ($r->pengajuan->so->name ?? '') . '</span><br>
                            <span>Tahun ' . $r->pengajuan->year . ' Semester ' . $r->pengajuan->semester . '</span>
                        </div>';
                }
            )
            ->editColumn(
                'status',
                function ($r) use ($user) {
                    $temp = SummaryKandidat::where('requirement_id', $r->id)->where('pengajuan_id', $r->pengajuan->id)->first();
                    if ($temp != null) {
                        return $temp->labelStatus($temp->status ?? 'new');
                    } else {
                        return \Base::getStatus('new');
                    }
                }
            )
            ->editColumn(
                'created_at',
                function ($r) {
                    $temp = SummaryKandidat::where('requirement_id', $r->id)->where('pengajuan_id', $r->pengajuan->id)->first();
                    if ($temp != null) {
                        if ($temp->status != 'new') {
                            return $temp->createdByRaw2();
                        }
                    }
                    return '';
                }
            )
            ->editColumn(
                'action',
                function ($r) use ($so_id) {
                    $actions = [];
                    $user = auth()->user();
                    $pengajuan_kandidat = SummaryKandidat::firstOrCreate([
                        'requirement_id' => $r->id,
                        'pengajuan_id' => $r->pengajuan->id,
                    ]);
                    if ($pengajuan_kandidat != null) {
                        if (in_array($pengajuan_kandidat->status, ['draft', 'submit', 'waiting.approval', 'completed', 'rejected'])) {
                            $actions[] = 'type:show|label:Detail|page:true|id:' . $pengajuan_kandidat->id;
                            if ($pengajuan_kandidat->checkAction('edit', $this->perms)) {
                                $actions[] = 'type:edit|page:true|id:' . $pengajuan_kandidat->id;
                            }

                            if ($user->checkPerms($this->perms . '.approve') && in_array($pengajuan_kandidat->status, ['waiting.approval', 'submit']) && $pengajuan_kandidat->checkApproval()) {
                                $actions[] = [
                                    'type' => 'show',
                                    'page' => true,
                                    'id' => $r->id,
                                    'label' => 'Approval',
                                    'icon' => 'fa fa-check text-primary',
                                    'url' => route($this->route . '.approval', $pengajuan_kandidat->id),
                                ];
                            }

                            if ($pengajuan_kandidat->status == 'completed' || $pengajuan_kandidat->status == 'waiting.approval') {
                                $actions[] = 'type:print|label:Cetak|page:true|id:' . $pengajuan_kandidat->id;
                            }

                            if ($pengajuan_kandidat->checkAction('tracking', $this->perms)) {
                                $actions[] = [
                                    'type' => 'show',
                                    'icon' => 'fas fa-chart-line text-info',
                                    'label' => 'Tracking Approval',
                                    'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                                    'url' => route($this->route . '.tracking', $pengajuan_kandidat->id),
                                    'id' => $pengajuan_kandidat->id,
                                ];
                            }

                            if ($pengajuan_kandidat->checkAction('show', $this->perms)) {
                                $actions[] = [
                                    'type' => 'show',
                                    'label' => 'Riwayat',
                                    'icon' => 'fa flaticon2-time text-success',
                                    'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                                    'url' => route($this->route . '.riwayat', $pengajuan_kandidat->id),
                                    'id' => $pengajuan_kandidat->id,
                                ];
                            }
                        } else {
                            if ($user->checkPerms($this->perms . '.edit')) {
                                $actions[] = [
                                    'type' => 'edit',
                                    'icon' => 'fa fa-plus text-primary',
                                    'label' => 'Tambah',
                                    'page' => true,
                                    'url' => route($this->route . '.edit', $pengajuan_kandidat->id),
                                    'id' => $pengajuan_kandidat->id,
                                ];
                            }
                        }
                    }
                    return $this->makeDropdown($actions);
                }
            )
            ->rawColumns(['action', 'created_at', 'status', 'tanggal_pengajuan', 'kandidat', 'pengajuan', 'vendor'])
            ->make(true);
    }


    public function index()
    {
        $this->setPrepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:pengajuan|label:Pengajuan|sortable:false|className:text-center'),
                        $this->makeColumn('name:tanggal_pengajuan|label:Unit Kerja & Periode|sortable:false|className:text-center'),
                        $this->makeColumn('name:vendor|label:Vendor & Posisi|sortable:false|className:text-center'),
                        $this->makeColumn('name:kandidat|label:Kandidat|sortable:false|className:text-center'),
                        $this->makeColumn('name:status|label:Status|sortable:false'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );
        $VENDOR = Vendor::orderBy('nama', 'ASC')
            ->get();
        $struct = OrgStruct::where('level', '!=', 'root')
            ->get()
            ->groupBy('level');
        return $this->render(
            $this->view . '.index',
            compact('VENDOR', 'struct')
        );
    }

    public function show($id)
    {
        $record = SummaryKandidat::with('pengajuan', 'requirement', 'details')->where('id', $id)->first();

        $grid = [
            $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
            $this->makeColumn('name:nama|label:Nama|sortable:false'),
            $this->makeColumn('name:nio|label:NIO|sortable:false'),
            $this->makeColumn('name:nik|label:NIK|sortable:false'),
            $this->makeColumn('name:status_nikah|label:Status Nikah|sortable:false'),
            $this->makeColumn('name:pas_foto|label:Pas Foto|sortable:false|className:text-center'),
            $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width: 160px'),
            $this->makeColumn('name:action|label:Opsi|sortable:false|width:40px'),
        ];
        $this->setPrepare(
            [
                'tableStruct' => [
                    'url' => route($this->route . '.personil.grid_show', $id),
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
        $record = SummaryKandidat::with('pengajuan', 'requirement', 'details')->where('id', $id)->first();

        $grid = [
            $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
            $this->makeColumn('name:nama|label:Nama|sortable:false'),
            $this->makeColumn('name:nio|label:NIO|sortable:false'),
            $this->makeColumn('name:nik|label:NIK|sortable:false'),
            $this->makeColumn('name:status_nikah|label:Status Nikah|sortable:false'),
            $this->makeColumn('name:pas_foto|label:Pas Foto|sortable:false'),
            $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width: 160px'),
            $this->makeColumn('name:action|label:Opsi|sortable:false|width:40px'),
        ];
        $this->setPrepare(
            [
                'tableStruct' => [
                    'url' => route($this->route . '.personil.grid', $id),
                    'datatable_1' => $grid
                ],
            ]
        );
        return $this->render(
            $this->view . '.detail',
            compact('record')
        );
    }

    public function personilGrid(SummaryKandidat $pengajuan)
    {
        $user = auth()->user();
        $temp = SummaryKandidat::with('pengajuan', 'requirement', 'details')->where('id', $pengajuan->id)->first();
        $records = $temp->details;
        $route = $this->route;
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
                    return '<span data-short="Active">' . $r->tad->nama ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'nio',
                function ($r) {
                    if(!empty($r->tad->kepegawaian)){
                        return '<span>' . $r->tad->kepegawaian->nio ?? '-' . "</span>";
                    }else{
                        return '-';
                    }
                }
            )
            ->editColumn(
                'nik',
                function ($r) {
                    return '<span data-short="Active">' . $r->tad->nik ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'status_nikah',
                function ($r) {
                    return '<span data-short="Active">' . $r->tad->status_perkawinan ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'jenis_kelamin',
                function ($r) {
                    return "<span>" . $r->tad->attr_jenis_kelamin ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'pas_foto',
                function ($r) {
                    return $this->render($this->view . '.partials.pas-foto', ['r' => $r->tad]);
                }
            )
            ->editColumn(
                'created_at',
                function ($r) use ($user) {
                    return $r->createdByRaw2();
                }
            )
            ->editColumn(
                'action',
                function ($r) use ($user, $temp, $route) {
                    $actions = [];
                    $actions[] = [
                        'type'      => 'show',
                        'label'     => 'Detail',
                        'page'      => true,
                        'id'        => $r->tad->id,
                        'url'       => $r->tad->kepegawaian_id
                            ? route('personil.aktif.show', ['aktif' => $r->tad->id, 'tab' => 'profile'])
                            : route('personil.belum-bekerja.show', ['belum_bekerja' => $r->tad->id, 'tab' => 'profile']),
                    ];
                    if (in_array($r->summary->status, ['new', 'draft', 'rejected'])) {
                        $actions[] = [
                            'type'      => 'delete',
                            'label'     => 'Hapus',
                            'attrs'     => 'data-confirm-text="Hapus Calon Kandidat ' . $r->tad->nama . '?"',
                            'id'        => $r->id,
                            'url'       => route($route . '.kandidatDestroy', $r->id),
                        ];
                    }
                    return $this->makeDropdown($actions);
                }
            )
            ->rawColumns(['nama', 'nik', 'created_at', 'action', 'jenis_kelamin', 'status_nikah', 'pas_foto', 'nio'])
            ->make(true);
    }

    public function personilGridShow(SummaryKandidat $pengajuan)
    {
        $user = auth()->user();
        $temp = SummaryKandidat::with('pengajuan', 'requirement', 'details')->where('id', $pengajuan->id)->first();
        $records = $temp->details;
        $route = $this->route;
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
                    return '<span>' . $r->tad->nama ?? '-' . "</span>";
                }
            )

            ->editColumn(
                'nio',
                function ($r) {
                    if(!empty($r->tad->kepegawaian)){
                        return '<span>' . $r->tad->kepegawaian->nio ?? '-' . "</span>";
                    }else{
                        return '-';
                    }
                }
            )
            ->editColumn(
                'nik',
                function ($r) {
                    return '<span>' . $r->tad->nik ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'status_nikah',
                function ($r) {
                    return '<span>' . $r->tad->status_perkawinan ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'jenis_kelamin',
                function ($r) {
                    return "<span>" . $r->tad->attr_jenis_kelamin ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'pas_foto',
                function ($r) {
                    return $this->render($this->view . '.partials.pas-foto', ['r' => $r->tad]);
                }
            )
            ->editColumn(
                'created_at',
                function ($r) use ($user) {
                    return $r->createdByRaw2();
                }
            )
            ->editColumn(
                'action',
                function ($r) use ($user, $temp, $route) {
                    $actions = [];
                    $actions[] = [
                        'type'      => 'show',
                        'label'     => 'Detail',
                        'page'      => true,
                        'id'        => $r->tad->id,
                        'url'       => $r->tad->kepegawaian_id
                            ? route('personil.aktif.show', ['aktif' => $r->tad->id, 'tab' => 'profile'])
                            : route('personil.belum-bekerja.show', ['belum_bekerja' => $r->tad->id, 'tab' => 'profile']),
                    ];
                    return $this->makeDropdown($actions);
                }
            )
            ->rawColumns(['nama', 'nik', 'created_at', 'action', 'jenis_kelamin', 'status_nikah', 'pas_foto', 'nio'])
            ->make(true);
    }

    public function detailCreate($id)
    {
        $record = SummaryKandidat::with('pengajuan', 'requirement')->where('id', $id)->first();

        $grid = [
            $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
            $this->makeColumn('name:check|label:Pilih|sortable:false|width:20px|className:text-center'),
            $this->makeColumn('name:pas_foto|label:Pas Foto|sortable:false|className:text-center|width:100px'),
            $this->makeColumn('name:nio|label:NIO|sortable:false'),
            $this->makeColumn('name:nama|label:Nama|sortable:false'),
            $this->makeColumn('name:status_nikah|label:Status Nikah|sortable:false'),
            $this->makeColumn('name:alamat|label:Alamat|sortable:false|className:text-left'),

        ];
        $this->setPrepare(
            [
                'tableStruct' => [
                    'url' => route($this->route . '.calonKandidat.grid', $id),
                    'datatable_2' => $grid
                ],
            ]
        );
        return $this->render(
            $this->view . '.detail.create',
            compact('record')
        );
    }

    public function calonKandidatGrid(SummaryKandidat $pengajuan)
    {
        $user = auth()->user();
        // $records = $pengajuan->details();
        $requirement = Requirement::find($pengajuan->requirement->id);

        // cek TAD yg sdh ada
        $ids = [];
        foreach ($pengajuan->details()->get() as $dd) {
            $ids[] = $dd->tad_id;
        }

        // record
        $records = Tad::with(
            [
                'kepegawaian',
                'lastEmployment',
                'pivotPengajuan',
            ]
        )->whereNotIn('id', $ids)
            ->where(
                function ($w) use ($requirement, $user) {
                    $w
                        ->where('vendor_id',      $requirement->vendor_id)
                        ->where('jabatan_id',     $requirement->jabatan_id)
                        ->when(
                            $jenis_kelamin = $requirement->jenis_kelamin,
                            function ($q) use ($jenis_kelamin) {
                                if ($jenis_kelamin !== 'LP') {
                                    $q->where('jenis_kelamin',  $jenis_kelamin);
                                }
                            }
                        )
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
            )->get();
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
                    return '<span>' . $r->nama ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'nio',
                function ($r) {
                    if(!empty($r->kepegawaian->nio)){
                        return '<span>' . $r->kepegawaian->nio ?? '-' . "</span>";

                    }
                    return '-';
                }
            )
            ->editColumn(
                'check',
                function ($r) use ($requirement) {
                    return '<label class="checkbox">
                        <input type="checkbox" ' . '' . ' class="kandidat" data-max="' . $requirement->jumlah . '" name="kandidat_id[' . $r->id . ']" value="' . $r->id . '">
                        <span></span>
                    </label>';
                }
            )
            ->editColumn(
                'status_nikah',
                function ($r) {
                    return '<span>' . $r->status_perkawinan ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'jenis_kelamin',
                function ($r) {
                    return "<span>" . $r->attr_jenis_kelamin ?? '-' . "</span>";
                }
            )
            ->editColumn(
                'pas_foto',
                function ($r) {
                    return $this->render($this->view . '.partials.pas-foto', ['r' => $r]);
                }
            )
            ->editColumn(
                'alamat',
                function ($r) {
                    return $r->alamat_lengkap;
                }
            )
            ->rawColumns(['nama', 'check', 'jenis_kelamin', 'status_nikah', 'pas_foto', 'nio'])
            ->make(true);
    }

    public function detailStore(SummaryKandidat $pengajuan, Request $request)
    {
        $requirement = Requirement::find($pengajuan->requirement->id);
        $request->request->add(
            [
                'jumlah_requirement' => $requirement->jumlah,
                'summary_id' => $pengajuan->id,
            ]
        );
        return $pengajuan->storeKandidatByRequest($request);
    }

    public function kandidatDestroy($id)
    {
        $kandidat = DetailKandidat::find($id);
        return $kandidat->deleteByRequest();
    }

    public function update(Request $request, $id)
    {
        // dd(205, $request->all());
        $record = SummaryKandidat::with('pengajuan', 'requirement')->where('id', $id)->first();
        return $record->updateByRequest($request);
    }

    public function approval($id)
    {
        $record = SummaryKandidat::with('pengajuan', 'requirement', 'details')->where('id', $id)->first();

        $grid = [
            $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
            $this->makeColumn('name:nama|label:Nama|sortable:false'),
            $this->makeColumn('name:nio|label:NIO|sortable:false'),
            $this->makeColumn('name:nik|label:NIK|sortable:false'),
            $this->makeColumn('name:status_nikah|label:Status Nikah|sortable:false'),
            $this->makeColumn('name:pas_foto|label:Pas Foto|sortable:false|className:text-center'),
            $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width: 160px'),
            $this->makeColumn('name:action|label:Opsi|sortable:false|width:40px'),
        ];
        $this->setPrepare(
            [
                'tableStruct' => [
                    'url' => route($this->route . '.personil.grid_show', $id),
                    'datatable_1' => $grid
                ],
            ]
        );
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
        $record = SummaryKandidat::with('pengajuan', 'requirement')->where('id', $id)->first();
        return $record->handleApproveSave($request);
    }

    public function riwayat($id)
    {
        $record = SummaryKandidat::find($id);
        $this->setPrepare(
            [
                'title'  => 'Kandidat | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->pengajuan->no_tiket . '</span>',
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
        $record = SummaryKandidat::find($id);
        $this->setPrepare(
            [
                'title'  => 'Kandidat | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->pengajuan->no_tiket . '</span>',
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
        $record = SummaryKandidat::with('pengajuan', 'requirement')->where('id', $id)->first();


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

        return $pdf->stream('Kandidat TAD.pdf', 'Kandidat TAD');
    }
}
