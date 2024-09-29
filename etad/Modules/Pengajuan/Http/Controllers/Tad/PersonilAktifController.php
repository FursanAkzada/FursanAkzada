<?php

namespace Modules\Pengajuan\Http\Controllers\Tad;

use App\Exports\PersonilTadTemplateExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Master\Entities\Geografis\City;
use Modules\Master\Entities\Jurusan;
use Modules\Master\Entities\KategoriVendor;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Keluarga;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\RiwayatKerja;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Tad\TadMigrasi;
use Modules\Master\Entities\Tad\WawancaraVendor;
use Modules\Master\Entities\Vendor;
use Modules\Master\Entities\Wawancara\Kompetensi;
use Modules\Master\Http\Requests\Tad\KeluargaRequest;
use Modules\Master\Http\Requests\Tad\KeluargaUpdateRequest;
use Modules\Master\Http\Requests\Tad\RiwayatKerjaRequest;
use Modules\Master\Http\Requests\Tad\TadRequest;
use Modules\Pengajuan\Entities\Tad\Kandidat;
use Modules\Pengajuan\Entities\Tad\Pengajuan;
use Modules\Pengajuan\Entities\Tad\SummaryWawancara;
use Modules\Pengajuan\Entities\Tad\Wawancara\Wawancara;
use Modules\Punishment\Entities\Pembinaan\Pembinaan;
use Modules\Punishment\Entities\Reward\Reward;
use Yajra\DataTables\DataTables;


class PersonilAktifController extends Controller
{
    protected $module = 'personil-aktif';
    protected $route  = 'personil.aktif';
    protected $view   = 'pengajuan::tad.personil'; // kandidat.wawancara.create
    protected $perms  = 'personil.aktif';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module'     => $this->module,
                'route'      => $this->route,
                'view'       => $this->view,
                'perms' => $this->perms,
                'title'      => 'Personil Bekerja',
                'breadcrumb' => [
                    'Personil & Quota'     =>  route($this->route . '.index'),
                    'Personil Bekerja'  => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function ajax(Request $request)
    {
        $user       = auth()->user();
        $user_so_id = $user->position->org_struct_id ?? null;
        $jabatan_id = $request->jabatan_id;
        $so_id      = $request->so_id;

        return response()->json(
            Tad::whereHas(
                'kepegawaian',
                function ($q) use ($user, $user_so_id, $so_id) {
                    $q
                        ->whereNull('resign_at')
                        ->whereNull('out_at')
                        ->where('cabang_id', $so_id);
                }
            )
                ->when(
                    $user->isVendor,
                    function ($q) use ($user) {
                        $q->where('vendor_id', $user->vendor_id);
                    }
                )
                ->when(
                    $id_not_in = $request->id_not_in,
                    function ($q) use ($id_not_in) {
                        $q->whereNotIn('id', explode(',', $id_not_in));
                    }
                )
                ->when(
                    $jabatan_id = $request->jabatan_id,
                    function ($q) use ($jabatan_id) {
                        $q->where('jabatan_id', $jabatan_id);
                    }
                )
                ->orderBy('nama', 'ASC')
                ->get()
        );
    }

    public function ajaxPenilaianTAD(Request $request)
    {
        return Tad::ajaxPenilaianTAD($request);
    }

    public function ajaxPenilaianTADCekJabatan(Request $request)
    {
        return Tad::ajaxPenilaianTADCekJabatan($request);
    }

    public function ajaxPenilaianTADCekPosisi(Request $request)
    {
        return Tad::ajaxPenilaianTADCekPosisi($request);
    }

    public function grid()
    {
        $user = auth()->user();
        $so_id = $user->position->org_struct_id ?? null;
        $records = Tad::with('jabatan', 'kepegawaian', 'vendor', 'riwayatKerja', 'lastEmployment')
            ->where(
                function ($q) {
                    $q
                        ->whereHas(
                            'kepegawaian',
                            function ($q) {
                                $q
                                    ->where('status', Kepegawaian::RECRUITMENT);
                                    // ->where('in_at', '<', now()->format('Y-m-d'));
                            }
                        )
                        ->orWhereHas(
                            'kepegawaian',
                            function ($q) {
                                $q->where('status', '!=', Kepegawaian::RECRUITMENT);
                            }
                        );
                }
            )
            ->when(
                $user->isEhc && !$user->cekDivisiHC() && ($so_id),
                function ($qqq) use ($so_id, $user) {
                    $qqq->whereHas('lastEmployment', function ($qqqq) use ($so_id) {
                        $qqqq->where('cabang_id', $so_id);
                    });
                }
            )
            ->when(
                $name = request()->name,
                function ($q) use ($name) {
                    $q->where(
                        function ($q) use ($name) {
                            $q
                                ->where('nama', 'LIKE', '%' . $name . '%')
                                ->orWhere('email', 'LIKE', '%' . $name . '%')
                                ->orWhereHas(
                                    'kepegawaian',
                                    function ($q) use ($name) {
                                        $q->where('nio', 'LIKE', '%' . $name . '%');
                                    }
                                );
                        }
                    );
                }
            )
            ->when(
                $unit_kerja_id = request()->unit_kerja_id,
                function ($q) use ($unit_kerja_id) {
                    $q->whereHas(
                        'kepegawaian',
                        function ($w) use ($unit_kerja_id) {
                            $w->where('cabang_id', $unit_kerja_id);
                        }
                    );
                }
            )
            ->when(
                $jabatan_id = request()->jabatan_id,
                function ($q) use ($jabatan_id) {
                    $q->where('jabatan_id', $jabatan_id);
                }
            )
            ->when(
                $user->isVendor,
                function ($q) use ($user) {
                    $q->where('vendor_id', request()->vendor_id ?? $user->vendor_id);
                }
            )
            ->when(
                $vendor_id = request()->vendor_id,
                function ($q) use ($vendor_id) {
                    $q->where('vendor_id', $vendor_id);
                }
            )
            ->when(
                $status = request()->status,
                function ($q) use ($status) {
                    if ($status === 'work') {
                        $q->whereHas(
                            'kepegawaian',
                            function ($q) use ($status) {
                                $q->whereIn('status', [
                                    Kepegawaian::WORK,
                                    Kepegawaian::MUTATION,
                                    Kepegawaian::RECRUITMENT,
                                    Kepegawaian::EXTENSION,
                                    Kepegawaian::MIGRATE,
                                ]);
                            }
                        );
                    } else if ($status === 'resign') {
                        $q->whereRelation('kepegawaian', 'status', Kepegawaian::RESIGN);
                    }
                }
            )
            // ->orderBy('kepegawaian_id', 'DESC')
            ->orderBy('updated_at', 'DESC');

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
                    return $this->render($this->view . '.partials.grid.nama', ['r' => $r]);
                }
            )
            ->editColumn(
                'nio',
                function ($r) {
                    if (isset($r->lastEmployment) && $r->lastEmployment->status == Kepegawaian::RESIGN) {
                        return $r->lastEmployment->nio;
                    }
                    return $r->kepegawaian->nio;
                }
            )
            ->editColumn(
                'jenis_kelamin',
                function ($r) {
                    return $r->jenis_kelamin;
                }
            )
            ->editColumn(
                'jabatan',
                function ($r) {
                    if ($r->kepegawaian->status) {
                        return $r->kepegawaian->jabatan->NM_UNIT;
                    }
                    return $r->jabatan->NM_UNIT;
                }
            )
            ->editColumn(
                'vendor',
                function ($r) {
                    return $r->vendor->nama;
                }
            )
            ->editColumn(
                'status_pekerja',
                function ($r) {
                    return $this->render(
                        $this->view . '.partials.grid.status-pekerja',
                        ['r' => $r]
                    );
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
                    $buttons[] = [
                        'type' => 'show',
                        'page' => true,
                        'id' => $r->id,
                        'url' => route($this->route . '.show', ['aktif' => $r->id, 'tab' => 'profile']),
                    ];
                    if (auth()->user()->checkPerms($this->perms . '.edit')) {
                        // if (auth()->user()->checkPerms($this->perms . '.edit') && $r->canEdit()) {
                        $buttons[] = [
                            'type' => 'custom',
                            'page' => true,
                            'id' => $r->id,
                            'label' => 'Ubah Keluarga',
                            'icon' => 'fas fa-users text-primary',
                            'url' => route($this->route . '.keluarga', ['personil' => $r->id]),
                        ];
                        $buttons[] = 'type:edit|page:true|label:Ubah Personil|id:' . $r->id;
                    }
                    $buttons[] = [
                        'type' => 'show',
                        'page' => true,
                        'id' => $r->id,
                        'label' => 'Riwayat Kerja',
                        'icon' => 'fas fa-code-branch text-success',
                        'url' => route($this->route . '.riwayatKerja', ['personil' => $r->id]),
                    ];
                    return $this->makeDropdown($buttons);
                }
            )
            ->rawColumns(
                [
                    'nio',
                    'action', 'created_at', 'nama', 'jenis_kelamin', 'jabatan', 'vendor'
                ]
            )
            ->make(true);
    }
    public function index()
    {
        $user = auth()->user();
        $so_id = $user->position->org_struct_id ?? null;
        $column = [
            'datatable_1' => [
                $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                $this->makeColumn('name:nama|label:Nama|className:text-left'),
                $this->makeColumn('name:nio|label:NIO|className:text-center'),
                $this->makeColumn('name:jenis_kelamin|label:L/P|className:text-center'),
                $this->makeColumn('name:jabatan|label:Jabatan|sortable:false|className:text-center'),
                $this->makeColumn('name:vendor|label:Vendor|sortable:false|className:text-center'),
                $this->makeColumn('name:status_pekerja|label:Status|className:text-center'),
                $this->makeColumn('name:created_at|label:Diperbarui|width:120px'),
                $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
            ]
        ];

        $this->setPrepare(
            [
                // 'perms' => $this->perms.'.index',
                'tableStruct' => $column,
            ]
        );
        $KATEGORI_VENDOR = KategoriVendor::with(
            [
                'jabatanTad' => function ($q) {
                    $q->orderBy('NM_UNIT', 'ASC');
                }
            ]
        )
            ->orderBy('nama', 'ASC')
            ->get();
        $org_structs    = OrgStruct::when(
            $so_id,
            function ($q) use ($so_id) {
                if ($so_id == env('APP_HC_ID')) {
                } else {
                    $q->where('id', $so_id);
                }
            }
        )
            ->orderBy('name', 'ASC')
            ->get()
            ->groupBy('level');
        $VENDOR = Vendor::orderBy('nama', 'ASC')
            ->get();
        return $this->render(
            $this->view . '.index',
            compact('org_structs', 'KATEGORI_VENDOR', 'VENDOR')
        );
    }

    public function create()
    {
        return $this->render($this->view . '.create');
    }
    public function store(TadRequest $request)
    {
        return Tad::createByRequest($request);
    }

    public function show(Request $request, $id)
    {
        $record         = Tad::with('jabatan', 'kepegawaian.jabatan')->find($id);
        $pengajuan      = null;
        $kandidat       = null;
        $pengajuan_id   = $request->pengajuan_id;
        $requirement_id = $request->requirement_id;
        $kandidat_id    = $request->kandidat_id;
        $activeTab = $request->input('tab', ''); // Retrieve the 'tab' value from the request
        // dd(
        //     json_decode($record),
        // );

        // Store the active tab in the session
        $request->session()->put('active_tab', $activeTab);
        if ($pengajuan_id && $requirement_id && $kandidat_id) {
            $pengajuan = Pengajuan::with(
                [
                    'requirement' => function ($q) use ($requirement_id) {
                        $q->where('id', $requirement_id)
                            ->where('flag', 'process');
                    }
                ]
            )
                ->where('status', 'vendor.submit')
                ->whereHas(
                    'requirement',
                    function ($q) use ($requirement_id) {
                        $q->where('id', $requirement_id)
                            ->where('flag', 'process');
                    }
                )
                ->findOrFail($pengajuan_id);
            $kandidat = Kandidat::with('wawancara')->findOrFail($kandidat_id);
        }
        $wawancaraVendorGrid = [
            $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
            $this->makeColumn('name:title|label:Judul|sortable:false|className:text-left'),
            $this->makeColumn('name:berkas|label:Berkas|sortable:false|className:text-left'),
        ];
        if (auth()->user()->isVendor) {
            $wawancaraVendorGrid[] = $this->makeColumn('name:action|label:Opsi|sortable:false');
        }
        $this->setPrepare(
            [
                'title'  => 'Personil | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->nama . '</span>',
                'tableStruct' => [
                    'table_url_1' => route($this->route . '.keluarga.grid', $id),
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:nama|label:Nama|sortable:false|className:text-left'),
                        $this->makeColumn('name:alamat|label:Alamat|sortable:false|className:text-left'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false'),
                    ],
                    'table_url_2' => route($this->route . '.wawancara.grid', $id),
                    'datatable_2' => $wawancaraVendorGrid,
                    'table_url_3' => route($this->route . '.wawancara-hc.grid', $id),
                    'datatable_3' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:pengajuan|label:No. Pengajuan|sortable:false|className:text-center'),
                        $this->makeColumn('name:wawancara|label:Tanggal Wawancara|sortable:false|className:text-center'),
                        $this->makeColumn('name:skor|label:Skor|sortable:false|className:text-center'),
                        $this->makeColumn('name:pewawancara|label:Pewawancara|sortable:false|className:text-left'),
                        // $this->makeColumn('name:kesimpulan|label:Kesimpulan|sortable:false'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false'),
                    ],
                    'table_url_4' => route($this->route . '.riwayat-kepegawaian.grid', $id),
                    'datatable_4' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:tgl_mutasi|label:Tanggal Mutasi|sortable:false|className:text-center'),
                        $this->makeColumn('name:tgl_efektif|label:Tanggal Efektif|sortable:false|className:text-center'),
                        $this->makeColumn('name:jenis_mutasi|label:Jenis Mutasi|sortable:false|className:text-center'),
                        $this->makeColumn('name:unit_kerja|label:Unit Kerja|sortable:false|className:text-left|width:350px'),
                        // $this->makeColumn('name:action|label:Opsi|sortable:false'),
                    ],
                    'table_url_5' => route($this->route . '.riwayatKerja.grid', $id),
                    'datatable_5' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:pekerjaan|label:Pekerjaan|sortable:false|className:text-left'),
                        $this->makeColumn('name:tanggal|label:Tanggal|sortable:false|className:text-center'),
                        $this->makeColumn('name:action_show|label:Opsi|sortable:false|width:40px'),
                    ],
                    'table_url_6' => route($this->route . '.reward.grid', $id),
                    'datatable_6' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:tgl|label:Tanggal|sortable:false|className:text-center'),
                        $this->makeColumn('name:jenis|label:Jenis|sortable:false|className:text-center'),
                        $this->makeColumn('name:desc|label:Deskripsi|sortable:false|className:text-center'),
                        $this->makeColumn('name:unit_kerja|label:Unit Kerja|sortable:false|className:text-left'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false'),
                    ]
                ]
            ]
        );
        return $this->render(
            $this->view . '.show',
            compact('record', 'pengajuan', 'kandidat', 'activeTab')
        );
    }

    public function edit($id)
    {
        // return 11;
        $record = Tad::with('kepegawaian.unitKerja')->find($id);
        return $this->render(
            $this->view . '.edit',
            compact('record')
        );
    }
    public function update(TadRequest $request, $id)
    {
        $tad = Tad::find($id);
        return $tad->updateByRequest($request);
    }

    // KELUARGA
    public function keluargaGrid($id)
    {
        $tad = Tad::find($id);
        $records = $tad->keluarga;
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
                    return '<div>
                <span>' . $r->nama . '</span><br>
                <span class="text-bold">Sebagai </span><span> : ' . $r->tipeKeluarga->tipe . '</span>
                </div>';
                }
            )
            ->editColumn(
                'ttl',
                function ($r) {
                    return $r->tempat_lahir . ', ' . $r->tanggal_lahir->format('d/m/Y');
                }
            )
            ->editColumn(
                'alamat',
                function ($r) {
                    return nl2br($r->tempat_lahir) . '<br>
                <span class="text-bold">Telp : </span><span>' . $r->telepon . '</span>';
                }
            )
            ->editColumn(
                'action',
                function ($r) use ($tad) {
                    $buttons = $this->makeDropdown(
                        [
                            // 'type:custom|page:false|icon:fas fa-eye text-primary|label:Detail|page:true|url:keluarga/' . $r->id.'/show',

                            // 'type:show|id:' . $r->id,
                            [
                                'type'  => 'show',
                                'label' => 'Lihat',
                                'id'    => $r->id,
                                'url'   => route($this->route . '.keluarga.show', $r->id)
                            ],
                            [
                                'type'  => 'edit',
                                'label' => 'Ubah',
                                'id'    => $r->id,
                                'url'   => route($this->route . '.keluarga.detailEdit', $r->id)
                            ],
                            [
                                'type'  => 'delete',
                                'label' => 'Hapus',
                                'id'    => $r->id,
                                'attrs' => 'data-confirm-text="Hapus ' . $r->nama . '?"',
                                'url'   => route($this->route . '.keluarga.destroy', $r->id)
                            ]
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(['action', 'nama', 'alamat'])
            ->make(true);
    }
    public function keluarga($id)
    {
        $record = Tad::with('keluarga')->find($id);

        $grid = [
            $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
            $this->makeColumn('name:nama|label:Nama|sortable:false|className:text-left'),
            $this->makeColumn('name:alamat|label:Alamat|sortable:false|className:text-left'),
            $this->makeColumn('name:action|label:Opsi|sortable:false'),
        ];

        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Personil Aktif',
                'tableStruct' => [
                    'url' => route($this->route . '.keluarga.grid', $record->id),
                    'datatable_1' => $grid
                ],
                'breadcrumb' => [
                    'Personil Aktif' => route($this->route . '.index'),
                    'Data Keluarga' => route($this->route . '.keluarga', $record->id),
                ]
            ]
        );

        $view = '.keluarga.create';
        return $this->render(
            $this->view . $view,
            compact('record')
        );
    }

    public function keluargaDetailCreate($id)
    {
        $record = Tad::find($id);
        return $this->render($this->view . '.keluarga.modal-create', compact('record'));
    }

    public function keluargaDetailEdit($id)
    {
        $record = Keluarga::find($id);
        return $this->render($this->view . '.keluarga.modal-edit', compact('record'));
    }

    public function keluargaDetailShow($id)
    {
        $record = Keluarga::find($id);
        return $this->render($this->view . '.keluarga.modal-show', compact('record'));
    }

    public function keluargaStore(KeluargaRequest $request, $id)
    {
        $tad = Tad::find($id);
        return $tad->createKeluargaByRequest($request);
    }

    public function keluargaDetailUpdate(KeluargaUpdateRequest $request, $id)
    {
        $keluarga = Keluarga::find($id);
        return $keluarga->updateByRequest($request);
    }

    public function keluargaShow($id)
    {
        $record = Keluarga::find($id);
        return $this->render($this->view . '.keluarga.show', compact('record'));
    }

    public function keluargaUpdate(KeluargaRequest $request, $id)
    {
        $tad = Tad::find($id);
        return $tad->updateKeluargaByRequest($request);
    }
    public function keluargaDestroy($id, Request $request)
    {
        $keluarga = Keluarga::find($id);
        return $keluarga->deleteByRequest($request);
    }

    public function kandidatEdit($id)
    {
        $user = auth()->user();
        $record = Kandidat::with('requirement.jabatan', 'requirement.kandidat', 'requirement.kandidats', 'tad')->findOrFail($id);
        $requirement = $record->requirement;
        $TAD = Tad::where(
            function ($w) use ($requirement) {
                $w->whereDoesntHave('kepegawaian');
                $w->where('vendor_id', $requirement->vendor_id);
                $w->where('jenis_kelamin', $requirement->jenis_kelamin);
                $w->where('jabatan_id', $requirement->jabatan_id);
                // $w->whereHas('keluarga');
                $w->where(
                    function ($wh) use ($requirement) {
                        $wh->whereHas(
                            'pivotPengajuan',
                            function ($p) use ($requirement) {
                                $p->where('id', $requirement->pengajuan_id);
                            }
                        )->orWhereDoesntHave('pivotPengajuan');
                    }
                );
            }
        )->orWhere(
            function ($ow) use ($requirement, $user) {
                $ow
                    ->where('vendor_id',      $user->vendor_id)
                    ->where('jabatan_id',     $requirement->jabatan_id)
                    ->where('jenis_kelamin',  $requirement->jenis_kelamin)
                    ->whereHas(
                        'kepegawaian',
                        function ($k) {
                            $k->whereNotNull('out_at');
                        }
                    )
                    ->where(
                        function ($wh) use ($requirement) {
                            $wh->whereHas(
                                'pivotPengajuan',
                                function ($p) use ($requirement) {
                                    $p->where('id', $requirement->pengajuan_id);
                                }
                            )->orWhereDoesntHave('pivotPengajuan');
                        }
                    );
            }
        )
            // ->whereNotIn('id', $requirement->kandidats->pluck('tad_id')->toArray())
            ->get()
            ->whereNotIn('id', $requirement->kandidats->pluck('tad_id')->toArray());
        // dd(540, $requirement->kandidats->pluck('tad_id')->toArray(), json_decode($requirement), $TAD->pluck('id'));

        return $this->render(
            $this->view . '.kandidat.edit',
            compact('record', 'TAD')
        );
    }
    public function kandidatUpdate(Request $request, $id)
    {
        $request->validate(
            [
                'kandidat_baru'    => 'required'
            ],
            [
                'kandidat_baru'    => 'Kandidat Baru'
            ]
        );
        $kandidat = Kandidat::with('wawancara')->findOrFail($id);
        return $kandidat->updateDitolakByRequest($request);
    }

    // RIWAYAT KERJA
    public function riwayatKerja($id)
    {
        $record = Tad::with('riwayatKerjaBefore')->find($id);

        $grid = [
            $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
            $this->makeColumn('name:pekerjaan|label:Pekerjaan|sortable:false|className:text-left'),
            $this->makeColumn('name:tanggal|label:Tanggal|sortable:false|className:text-center'),
            $this->makeColumn('name:action|label:Opsi|sortable:false'),
        ];

        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Personil Aktif',
                'tableStruct' => [
                    'url' => route($this->route . '.riwayatKerja.grid', $record->id),
                    'datatable_1' => $grid
                ],
                'breadcrumb' => [
                    'Personil Aktif' => route($this->route . '.index'),
                    'Data Riwayat Kerja' => route($this->route . '.riwayatKerja', $record->id),
                ]
            ]
        );

        $view = '.riwayat-kerja.index';
        return $this->render(
            $this->view . $view,
            compact('record')
        );
    }
    public function riwayatKerjaGrid($id)
    {
        $tad = Tad::find($id);
        $records = $tad->riwayatKerjaBefore;
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'pekerjaan',
                function ($r) {
                    return '<div><span class="text-bold">' . $r->title . '</span><br>' . $r->company . ' | ' . $r->employment_type . '</div>';
                }
            )
            ->editColumn(
                'tanggal',
                function ($r) {
                    return $r->start_date->translatedFormat('d F Y') . 's/d' . $r->end_date->translatedFormat('d F Y');
                }
            )
            ->editColumn(
                'action_show',
                function ($r) use ($tad) {
                    $buttons = $this->makeDropdown(
                        [
                            [
                                'type'  => 'show',
                                'label' => 'Lihat',
                                'id'    => $r->id,
                                'url'   => route($this->route . '.riwayatKerja.show', $r->id)
                            ],
                        ]
                    );
                    return $buttons;
                }
            )
            ->editColumn(
                'action',
                function ($r) use ($tad) {
                    $buttons = $this->makeDropdown(
                        [
                            [
                                'type'  => 'show',
                                'label' => 'Lihat',
                                'id'    => $r->id,
                                'url'   => route($this->route . '.riwayatKerja.show', $r->id)
                            ],
                            [
                                'type'  => 'edit',
                                'label' => 'Ubah',
                                'id'    => $r->id,
                                'url'   => route($this->route . '.riwayatKerja.edit', $r->id)
                            ],
                            [
                                'type'  => 'delete',
                                'label' => 'Hapus',
                                'id'    => $r->id,
                                'attrs' => 'data-confirm-text="Hapus ' . $r->nama . '?"',
                                'url'   => route($this->route . '.riwayatKerja.destroy', $r->id)
                            ]
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(['action', 'pekerjaan', 'tanggal', 'action_show'])
            ->make(true);
    }

    public function riwayatKerjaCreate($id)
    {
        $record = Tad::find($id);
        return $this->render($this->view . '.riwayat-kerja.create', compact('record'));
    }

    public function riwayatKerjaStore(RiwayatKerjaRequest $request, $id)
    {
        $tad = Tad::find($id);
        $detail = new RiwayatKerja;
        $detail->save();
        return $tad->updateRiwayatKerjaByRequest($request, $detail);
    }

    public function riwayatKerjaEdit($id)
    {
        $record = RiwayatKerja::find($id);
        return $this->render($this->view . '.riwayat-kerja.edit', compact('record'));
    }

    public function riwayatKerjaUpdate(RiwayatKerjaRequest $request, $id)
    {
        $detail = RiwayatKerja::find($id);
        $record = $detail->tad;
        return $record->updateRiwayatKerjaByRequest($request, $detail);
    }

    public function riwayatKerjaShow($id)
    {
        $record = RiwayatKerja::find($id);
        return $this->render($this->view . '.riwayat-kerja.show', compact('record'));
    }

    public function riwayatKerjaDestroy($id)
    {
        $detail = RiwayatKerja::find($id);
        $record = $detail->tad;
        return $record->handleRiwayatKerjaDestroy($detail);
    }

    /* Wawancara */
    public function wawancaraGrid($id)
    {
        $records = WawancaraVendor::with('files')
            ->where('tad_id', $id)
            ->get();
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'berkas',
                function ($r) {
                    $lists = '<ul>';
                    foreach ($r->files as $file) {
                        $lists .= "<li><a href='{$file->url}' target='_blank'>{$file->name}</a></li>";
                    }
                    $lists .= '</ul>';
                    return $lists;
                }
            )
            ->editColumn(
                'action',
                function ($r) {
                    $buttons = $this->makeDropdown(
                        [
                            [
                                'type'  => 'edit',
                                'class' => 'base-modal--render',
                                'page'  => 'false',
                                'label' => 'Ubah',
                                'id'    => $r->id,
                                'url'   => route($this->route . '.wawancara.edit', $r->id)
                            ],
                            [
                                'type'  => 'delete',
                                'label' => 'Hapus',
                                'attrs' => 'data-confirm-text="Hapus ' . $r->title . '?"',
                                'id'    => $r->id,
                                'url'   => route($this->route . '.wawancara.delete', $r->id)
                            ],
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(['berkas', 'action'])
            ->make(true);
    }
    public function wawancaraCreate($id)
    {
        $personil = Tad::findOrFail($id);
        return $this->render(
            $this->view . '.wawancara.create',
            compact('personil')
        );
    }
    public function wawancaraStore($id, Request $request)
    {
        return WawancaraVendor::createByRequest($request);
    }
    public function wawancaraShow(Wawancara $wawancara)
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Detail Wawancara HC',
                'breadcrumb' => [
                    'Pengajuan TAD' => '/',
                    'Wawancara HC' => '/',
                ]
            ]
        );
        return $this->render(
            $this->view . '.wawancara.show',
            [
                'kompetensi' => Kompetensi::orderBy('kompetensi', 'desc')->get(),
                'wawancara' => $wawancara,
            ]
        );
    }

    public function wawancaraEdit($id)
    {
        $wawancara = WawancaraVendor::findOrFail($id);
        return $this->render(
            $this->view . '.wawancara.edit',
            compact('wawancara')
        );
    }
    public function wawancaraUpdate(Request $request, $id)
    {
        $record = WawancaraVendor::findOrFail($id);
        return $record->updateByRequest($request);
    }
    public function wawancaraDelete(Request $request, $id)
    {
        $record = WawancaraVendor::findOrFail($id);
        return $record->deleteByRequest($request);
    }

    public function wawancaraHcCreate(Request $request, Kandidat $kandidat)
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'perms'  => 'pengajuan.tad.form.approve',
                'route'  => $this->route,
                'title'  => 'Form Wawancara TAD',
                'breadcrumb' => [
                    'Pengajuan TAD' => '/',
                    'Otorisasi Human Captial' => '/',
                    'Wawancara TAD' => '/',
                ]
            ]
        );
        return $this->render(
            $this->view . '.wawancara-hc.create',
            [
                'tad'           => $kandidat->tad,
                'kandidat'      => $kandidat,
                'kompetensi'    => Kompetensi::whereHas('pertanyaan')
                    ->orderBy('kompetensi', 'desc')
                    ->get(),
                'pengajuan'     => $kandidat->requirement->pengajuan,
                'query'         => $request->query(),
            ]
        );
    }
    public function wawancaraHcStore(Kandidat $kandidat, Request $request)
    {
        $request->validate(
            [
                'tgl'           => ['required'],
                'pewawancara'   => ['required'],
                'question.*'    => ['required'],
                'kesimpulan'    => ['required', 'in:1,2,3,4,5,6'],
                'saran'         => ['required']
            ]
        );
        // $request->request->add(
        //     [
        //         // 'current_url' => url()->previous(),
        //         // 'current_url' => $request->redirect_to,
        //     ]
        // );
        return $kandidat->wawancaraCreateByRequest($request);
    }

    public function wawancaraHcGrid($tad_id)
    {
        $records = SummaryWawancara::whereHas(
            'kandidat',
            function ($w) use ($tad_id) {
                $w->where('tad_id', $tad_id);
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
                'skor',
                function ($r) {
                    return "<span>" . $r->details->sum('value') . "</span>";
                }
            )
            ->editColumn(
                'no_tiket',
                function ($r) {
                    return '<div>
            <span class="text-bold">No : </span><span>' . $r->kandidat->summary->pengajuan->no_tiket . '</span><br>
            <span class="text-bold">Tanggal : </span><span>' . $r->tgl_wawancara . '</span>
            </div>';
                }
            )
            ->editColumn(
                'pengajuan',
                function ($r) {
                    return $r->kandidat->summary->pengajuan->no_tiket;
                }
            )
            ->editColumn(
                'wawancara',
                function ($r) {
                    if ($r->is_fallback != 0) {
                        return 'Mengundurkan diri';
                    } else {
                        return $r->tgl_wawancara->format('d/m/Y');
                    }
                }
            )
            ->editColumn(
                'pewawancara',
                function ($r) {
                    $list = '<ul>';
                    foreach ($r->pewawancaras as $key => $dd) {
                        $list .= '<li>' . $dd->user->name . '</li>';
                    }
                    $list .= '</ul>';
                    return $list;
                }
            )
            ->editColumn(
                'kesimpulan',
                function ($r) {
                    return $r->keterangan;
                }
            )
            ->editColumn(
                'action',
                function ($r) {
                    $buttons = $this->makeDropdown(
                        [
                            [
                                'type' => 'custom',
                                'icon' => 'fas fa-eye text-primary',
                                'label' => 'Detail',
                                'attrs' => 'data-modal-size="modal-xl"',
                                'id'    => $r->id,
                                'url' => route($this->route . '.kandidat.wawancara.show', $r->id),
                            ],
                            // 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->name . '?"',
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(
                [
                    'action',
                    'posisi',
                    'pengajuan',
                    'wawancara',
                    'pewawancara',
                    'kesimpulan',
                    'skor',
                    'no_tiket',
                ]
            )
            ->make(true);
    }
    public function wawancaraHcShow($id)
    {
        $wawancara = SummaryWawancara::with(
            'details',
            'kandidat',
            'kandidat.tad',
            'kandidat.summary',
            'kandidat.summary.pengajuan',
            'kandidat.summary.requirement',
            'kandidat.summary.details'
        )->where('id', $id)->first();

        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Detail Wawancara HC',
                'breadcrumb' => [
                    'Pengajuan TAD' => '/',
                    'Otorisasi Human Captial' => '/',
                    'Wawancara TAD' => '/',
                ]
            ]
        );
        return $this->render(
            $this->view . '.wawancara-hc.show',
            [
                'kompetensi' => Kompetensi::orderBy('kompetensi', 'desc')->get(),
                'record' => $wawancara,
                // 'kandidat' => $wawancara->kandidat->tad,
            ]
        );
    }

    public function wawancaraHcEdit(Wawancara $wawancara)
    {
        $this->setPrepare(
            [
                'title'  => 'Ubah Hasil Wawancara TAD',
                'breadcrumb' => [
                    'Home' => '/',
                ]
            ]
        );
        return $this->render(
            $this->view . '.wawancara-hc.edit',
            [
                'kompetensi' => Kompetensi::orderBy('kompetensi', 'desc')->get(),
                'wawancara' => $wawancara,
                'kandidat' => $wawancara->kandidat,
                'pengajuan' => $wawancara->kandidat->requirement->pengajuan
            ]
        );
    }
    public function wawancaraHcUpdate(Wawancara $wawancara, Request $request)
    {
        $request->validate(
            [
                'question.*' => ['required'],
                'kesimpulan' => ['required', 'in:1,2,3,4,5,6'],
                'saran' => ['required']
            ]
        );
        return $wawancara->updateByRequest($request);
    }

    /* Helpers */
    public function kota(Request $request)
    {
        $request->validate(
            [
                'province_id' => ['required', 'exists:ref_city,province_id']
            ]
        );
        return City::where(['province_id' => $request->province_id])
            ->orderBy('name', 'ASC')->get();
    }
    public function jurusan(Request $request)
    {
        $request->validate(
            [
                'pendidikan_id' => ['required', 'exists:ref_jurusan,pendidikan_id']
            ]
        );
        return Jurusan::where(['pendidikan_id' => $request->pendidikan_id])
            ->orderBy('name', 'ASC')->get();
    }

    public function riwayatKepegawaianGrid($tad_id)
    {
        $records = Kepegawaian::with(
            [
                // 'mutationEmployment.pengajuanMutasiPegawai.pengajuan',
                // 'pengajuanMutasiPegawai.pengajuan',
                // 'pengajuanResignPegawai.pengajuan',
                // // 'previousEmployment',
                // 'kandidat.requirement.pengajuan',
                // 'tad'
            ]
        )
            ->where('tad_id', $tad_id)
            ->orderBy('id', 'DESC')
            ->get();

        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'jenis_mutasi',
                function ($r) {
                    return Kepegawaian::STATUS[$r->status] ?? '';
                }
            )
            ->editColumn(
                'tgl_mutasi',
                function ($r) {
                    return $r->created_at->translatedFormat('d/m/Y');
                }
            )
            ->editColumn(
                'tgl_efektif',
                function ($r) {
                    if ($r->status == Kepegawaian::MIGRATE) {
                        return $r->created_at->translatedFormat('d/m/Y');
                    }
                    if ($r->out_at) {
                        return $r->out_at->translatedFormat('d/m/Y');
                    }
                    if ($r->in_at) {
                        return $r->in_at->translatedFormat('d/m/Y');
                    }
                    return $r->created_at->translatedFormat('d/m/Y');
                }
            )
            ->editColumn(
                'unit_kerja',
                function ($r) {
                    return $this->render(
                        $this->view . '.partials.grid.unit-kerja',
                        ['r' => $r]
                    );
                }
            )
            ->editColumn(
                'action',
                function ($r) {
                    $buttons = [];
                    if ($r->status == Kepegawaian::RECRUITMENT) {
                        $buttons[] = [
                            'type'  => 'show',
                            'label' => 'Lihat',
                            'page'  => true,
                            'id'    => $r->kandidat_id,
                            'url'   => route('pengajuan.penerimaan.show', $r->kandidat_id),
                        ];
                    }
                    return $this->makeDropdown($buttons);
                }
            )
            ->rawColumns(['contract_due', 'mutation_at', 'resign_at', 'out_at', 'created_at', 'action', 'jenis_mutasi', 'tgl_mutasi'])
            ->make(true);
    }


    public function riwayatRewardGrid($id)
    {
        $records = [];
        $rewards = Reward::with(
            [
                'kepegawaian',
                'tad',
                'jenisPunishment'
            ]
        )
            ->whereIn('status', ['approved', 'completed'])
            ->where('tad_id', $id)
            ->orderBy('created_at', 'DESC')
            ->get();
        $pembinaans = Pembinaan::with(
            [
                'kepegawaian',
                'tad',
                'jenisPunishment'
            ]
        )
            ->whereIn('status', ['approved', 'completed'])
            ->where('tad_id', $id)
            ->orderBy('created_at', 'DESC')
            ->get();

        // Combine both sets of data
        $allData = $rewards->concat($pembinaans);

        // Sort the combined data by the 'date' field in descending order
        $sortedData = $allData->sortByDesc(function ($item) {
            return $item->tanggal_reward ?? $item->tanggal_sk;
        });

        foreach ($sortedData as $dd) {
            $jenis = isset($dd->tanggal_reward) ? 'Penghargaan' : 'Pembinaan';
            $date = $dd->tanggal_reward ?? $dd->tanggal_sk;

            $records[] = [
                'id' => $dd->id,
                'jenis' => $jenis,
                'desc'  => $dd->jenisPunishment,
                'date' => $date,
                'tad' => $dd->tad,
                'kepegawaian' => $dd->kepegawaian,
                'data'  => $dd,
            ];
        }

        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'jenis',
                function ($r) {
                    return $r['jenis'] ?? '';
                }
            )
            ->editColumn(
                'tgl',
                function ($r) {
                    return Carbon::parse($r['date'])->translatedFormat('d F Y');
                }
            )
            ->editColumn('desc', function ($r) {
                if ($r['jenis'] == 'Pembinaan') {
                    $tanggal_mulai = Carbon::parse($r['data']['tanggal_mulai'])->format('Y-m-d');
                    $tanggal_selesai = Carbon::parse($r['data']['tanggal_selesai'])->format('Y-m-d');
                    return $r['desc']['Lengkap'] . "<br>" . $this->getTanggalFormatMulaiSelesai($tanggal_mulai, $tanggal_selesai);
                }
                return $r['desc']['Lengkap'];
            })
            ->editColumn(
                'unit_kerja',
                function ($r) {
                    $kepegawaian = Kepegawaian::find($r['kepegawaian']['id']);
                    return $this->render(
                        $this->view . '.partials.grid.unit-kerja',
                        ['r' => $kepegawaian]
                    );
                }
            )
            ->editColumn(
                'action',
                function ($r) {
                    if ($r['jenis'] == "Penghargaan") {
                        $buttons[] = [
                            'type' => 'show',
                            'page' => true,
                            'id' => $r['id'],
                            'label' => 'Lihat',
                            'url' => route('reward.form' . '.show', $r['id']),
                        ];
                    } elseif ($r['jenis'] == "Pembinaan") {
                        $buttons[] = [
                            'type' => 'show',
                            'page' => true,
                            'id' => $r['id'],
                            'label' => 'Lihat',
                            'url' => route('pembinaan.form' . '.show', $r['id']),
                        ];
                    }

                    return $this->makeDropdown($buttons);
                }
            )
            ->rawColumns(['created_at', 'action', 'jenis_reward', 'tgl', 'unit_kerja', 'desc'])
            ->make(true);
    }

    // Fungsi untuk menggabungkan tanggal dengan format khusus
    public function getTanggalFormatMulaiSelesai($tanggal_awal, $tanggal_akhir)
    {
        $tanggal_awal = Carbon::createFromFormat('Y-m-d', $tanggal_awal);
        $tanggal_akhir = Carbon::createFromFormat('Y-m-d', $tanggal_akhir);

        $tahun_awal = date('Y', strtotime($tanggal_awal));
        $tahun_akhir = date('Y', strtotime($tanggal_akhir));

        $bulan_awal = date('m', strtotime($tanggal_awal));
        $bulan_akhir = date('m', strtotime($tanggal_akhir));

        $hari_awal = date('j', strtotime($tanggal_awal));
        $hari_akhir = date('j', strtotime($tanggal_akhir));

        return $tanggal_awal->translatedFormat('d M Y') . ' s/d ' . $tanggal_akhir->translatedFormat('d M Y');

        if ($tanggal_awal === $tanggal_akhir) {
            return $tanggal_awal->translatedFormat('d F Y');
        } elseif ($bulan_awal === $bulan_akhir && $tahun_awal === $tahun_akhir) {
            return $tanggal_awal->translatedFormat('d') . " - " . $tanggal_akhir->translatedFormat('d') . " " . $tanggal_awal->translatedFormat('F Y');
        } elseif ($tahun_awal === $tahun_akhir) {
            return $tanggal_awal->translatedFormat('d F') . " - " . $tanggal_akhir->translatedFormat('d F') . " " . $tanggal_awal->translatedFormat('Y');
        } else {
            return $tanggal_awal->translatedFormat('d F Y') . " - " . $tanggal_akhir->translatedFormat('d F Y');
        }
    }
}
