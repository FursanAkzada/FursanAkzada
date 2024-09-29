<?php

namespace Modules\Pengajuan\Http\Controllers\Tad;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Tad\Kandidat;
use Modules\Pengajuan\Entities\Tad\Pengajuan;
use Modules\Pengajuan\Entities\Tad\Requirement;
use Modules\Pengajuan\Entities\Tad\Wawancara\Wawancara;
use Yajra\DataTables\DataTables;

class OtorisasiVendorController extends Controller
{
    protected $module = 'pengajuan';
    protected $route  = 'pengajuan.tad.otorisasi.vendor';
    protected $view   = 'pengajuan::tad.otorisasi.vendor';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view' => $this->view,
                'title'  => 'Otorisasi Vendor',
                'breadcrumb' => [
                    'Pengajuan TAD' => '/',
                    'Otorisasi Vendor' => '/',
                ]
            ]
        );
    }

    public function grid()
    {
        $records = Pengajuan::otorisasiVendor()
            ->when($keyword = request()->keyword, function ($q) use ($keyword) {
                $q->whereLike(['no_tiket', 'created_at'], $keyword);
            });
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
                'kandidat',
                function ($r) {
                    return '<div>
                 <span><span class="text-bold text-info">Requirement : </span>' . $r->requirement()->curVendor()->get()->sum('jumlah') . '</span><br>
                 <span><span class="text-bold text-primary">Kandidat : </span>' . $r->kandidat()->whereHas('requirement', function ($q) {
                        $q->where('vendor_id', auth()->user()->vendor_id);
                    })->count() . '</span>
                </div>';
                }
            )
            ->editColumn(
                'tanggal_pengajuan',
                function ($r) {
                    return '<div>
                 <span><span class="text-bold">Dari : </span>' . ($r->cabang ? $r->cabang->CAPEM : '-') . '</span><br>
                 <span><span class="text-bold">Pada Tgl : </span>' . $r->created_at->format('d/m/Y') . '</span>
                </div>';
                }
            )
            ->editColumn(
                'status',
                function ($r) {
                    return $r->active_logs->statusRaw();
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
                    if ($r->active_logs->status == 'rewaiting.vendor') {
                        return $this->makeDropdown(
                            [
                                [
                                    'type' => 'custom',
                                    'icon' => 'fas fa-eye text-primary',
                                    'label' => 'Detail',
                                    'page' => true,
                                    'url' => route($this->route . '.reprocess', $r->id)
                                ]
                            ]
                        );
                    }
                    return $this->makeDropdown(
                        [
                            'type:show|label:Detail|page:true|id:' . $r->id,
                        ]
                    );
                }
            )
            ->rawColumns(['action', 'created_at', 'status', 'tanggal_pengajuan', 'kandidat'])
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
                        $this->makeColumn('name:no_tiket|label:Nomor Tiket|sortable:false|className:text-left'),
                        $this->makeColumn('name:tanggal_pengajuan|label:Tanggal Pengajuan|sortable:false|className:text-left'),
                        $this->makeColumn('name:kandidat|label:Kandidat|sortable:false|className:text-left'),
                        $this->makeColumn('name:status|label:Status|sortable:false'),
                        $this->makeColumn('name:created_at|label:Dibuat pada|sortable:false'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );
        return $this->render($this->view . '.index');
    }

    public function create()
    {
        return $this->render($this->view . '.create');
    }

    public function store(Pengajuan $otorisasi_vendor, Request $request)
    {
        return false;
    }

    public function show(Pengajuan $otorisasi_vendor)
    {
        $col = [
            $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
            $this->makeColumn('name:jabatan|label:Jabatan|sortable:false'),
            $this->makeColumn('name:jenis_kelamin|label:Jenis Kelamin|sortable:false'),
            $this->makeColumn('name:jumlah|label:Jumlah|sortable:false'),
            $this->makeColumn('name:kandidat|label:Kandidat|sortable:false'),
            $this->makeColumn('name:vendor|label:Vendor|sortable:false'),
        ];
        if ($otorisasi_vendor->submitted()) {
            $col = [
                $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                $this->makeColumn('name:jabatan|label:Jabatan|sortable:false'),
                $this->makeColumn('name:jenis_kelamin|label:Jenis Kelamin|sortable:false'),
                $this->makeColumn('name:jumlah|label:Jumlah|sortable:false'),
                $this->makeColumn('name:kandidat|label:Kandidat|sortable:false'),
                $this->makeColumn('name:vendor|label:Vendor|sortable:false'),
                $this->makeColumn('name:action|label:Opsi|sortable:false'),
            ];
        }
        $this->setPrepare(
            [
                'tableStruct' => [
                    'url' => route($this->route . '.requirement.grid', $otorisasi_vendor->id),
                    'datatable_1' => $col
                ],
            ]
        );

        return $this->render(
            $this->view . '.show',
            [
                'record' => $otorisasi_vendor,
            ]
        );
    }

    public function edit($id)
    {
        return false;
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy(Pengajuan $otorisasi_vendor)
    {
        return $otorisasi_vendor->deleteByRequest();
    }

    public function requirementGrid(Pengajuan $otorisasi_vendor)
    {
        $records = $otorisasi_vendor->requirement()->curVendor();
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'jabatan',
                function ($r) {
                    return $r->jabatan->NM_UNIT;
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
                    return $r->vendor->nama;
                }
            )
            ->editColumn(
                'action',
                function ($r) {
                    $buttons = $this->makeDropdown(
                        [
                            'type:custom|label:Isi Kandidat|icon:fas fa-users text-primary|url:kandidat/' . $r->id . '|attrs:data-modal-size="modal-lg"',
                            // 'type:show|label:Detail|page:true|id:' . $r->id,
                            // 'type:edit|id:' . $r->id,
                            // 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->name . '?"',
                        ]
                    );
                    return $buttons;
                }
            )
            ->make(true);
    }

    public function reprocess(Pengajuan $pengajuan)
    {
        $this->setPrepare(
            [
                'tableStruct' => [
                    'url' => route($this->route . '.reprocess.requirement.grid', $pengajuan->id),
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:jabatan|label:Jabatan|sortable:false'),
                        $this->makeColumn('name:jenis_kelamin|label:Jenis Kelamin|sortable:false'),
                        $this->makeColumn('name:jumlah|label:Jumlah|sortable:false'),
                        $this->makeColumn('name:kandidat|label:Kandidat|sortable:false'),
                        $this->makeColumn('name:vendor|label:Vendor|sortable:false'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false'),
                    ]
                ],
            ]
        );
        return $this->render(
            $this->view . '.reprocess.show',
            [
                'record' => $pengajuan
            ]
        );
    }

    public function reprocessRequirementGrid(Pengajuan $pengajuan)
    {
        $records = $pengajuan->requirement()->process()->curVendor();
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'jabatan',
                function ($r) {
                    return $r->jabatan->NM_UNIT;
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
                    return $r->vendor->nama;
                }
            )
            ->editColumn(
                'action',
                function ($r) {
                    return $this->makeDropdown(
                        [
                            [
                                'type' => 'custom',
                                'label' => 'Kandidat',
                                'icon' => 'fas fa-users text-primary',
                                'attrs' => 'data-modal-size="modal-lg"',
                                'url' => route($this->route . '.reprocess.kandidat', $r->id)
                            ]
                        ]
                    );
                }
            )
            ->make(true);
    }

    public function reprocessKandidat(Requirement $requirement)
    {
        $this->setPrepare(
            [
                'title'  => 'Kandidat',
                'tableStruct' => [
                    'url' => route($this->route . '.reprocess.kandidat.grid', $requirement->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:check|label:Assign|sortable:false|width:20px|className:text-center'),
                        $this->makeColumn('name:nama|label:Nama Kandidat|sortable:false'),
                        $this->makeColumn('name:status_wawancara|label:Status Wawancara|sortable:false|width:200px'),
                        $this->makeColumn('name:status_penerimaan|label:Penerimaan|sortable:false|width:200px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false'),
                    ]
                ],
            ]
        );
        return $this->render(
            $this->view . '.reprocess.kandidat',
            [
                'record' => $requirement
            ]
        );
    }

    public function reprocessKandidatGrid(Requirement $requirement)
    {
        // BUG: Allow all query
        $tad = Tad::where(
            function ($w) use ($requirement) {
                $w->where('vendor_id', auth()->user()->vendor_id);
                $w->where('jenis_kelamin', $requirement->jenis_kelamin);
                $w->where('jabatan_id', $requirement->jabatan_id);
                $w->whereHas('keluarga');
                $w->whereDoesntHave('kepegawaian');
            }
        )->orWhere(
            function ($ow) use ($requirement) {
                // $ow->whereIn('id', $requirement->kandidat->pluck('id'));
                $ow->whereHas(
                    'kepegawaian',
                    function ($k) {
                        $k->whereNotNull('out_at');
                    }
                );
            }
        )->get();
        $records = $requirement->kandidat->merge($tad);

        // dd($records);

        // dd($records->get());
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
                    $kandidat = Kandidat::where(['tad_id' => $r->id, 'requirement_id' => $requirement->id]);
                    $checked = ($kandidat->exists() ? 'checked' : '');

                    if (!is_null($kandidat = $kandidat->first())) {
                        if ($kandidat->accepted) {
                            return '
                        <input type="hidden" name="kandidat_id[' . $r->id . ']" value="' . $r->id . '">
                        <label class="checkbox checkbox-disabled">
                            <input type="checkbox" ' . $checked . ' disabled="disabled">
                            <span></span>
                        </label>';
                        }
                    }

                    return '<label class="checkbox">
                        <input type="checkbox" ' . $checked . ' name="kandidat_id[' . $r->id . ']" value="' . $r->id . '">
                        <span></span>
                    </label>';
                }
            )
            ->editColumn(
                'nama',
                function ($r) {
                    return $this->render('master::tad.partials.grid.nama', ['r' => $r]);
                }
            )
            ->editColumn(
                'status_wawancara',
                function ($r) {
                    if (is_null($r->kandidat)) {
                        return '<span class="badge badge-light">-</span>';
                    }

                    $wawancara = Wawancara::ongoing($r->kandidat->id)->first();
                    return !is_null($wawancara)
                        ? $wawancara->kesimpulanRaw()
                        : '<span class="badge badge-warning text-white">Belum Di Wawancara</span>';
                }
            )
            ->editColumn(
                'status_penerimaan',
                function ($r) {
                    if (is_null($r->kandidat)) {
                        return '<span class="badge badge-light">-</span>';
                    }

                    switch ($r->kandidat->accepted) {
                        case 1:
                            return '<span class="badge badge-success" data-toggle="" >Diterima</span>';
                            break;
                        case 2:
                            return '<span class="badge badge-danger" data-toggle="" >Ditolak</span>';
                            break;
                        default:
                            return '<span class="badge badge-light">Penentuan</span>';
                            break;
                    }
                }
            )
            ->editColumn(
                'action',
                function ($r) {
                    $buttons = $this->makeDropdown(
                        [
                            'type:show|label:Detail|page:true|id:' . $r->id,
                            // 'type:edit|id:' . $r->id,
                            // 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->name . '?"',
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(['check', 'action', 'status_wawancara', 'status_penerimaan'])
            ->make(true);
    }

    public function keluargaGrid(Tad $tad)
    {
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
                function ($r) {
                    $buttons = $this->makeDropdown(
                        [
                            'type:custom|page:true|icon:fas fa-eye text-primary|label:Detail|page:true|url:kandidat/' . $r->id . '/show',
                            // 'type:edit|id:' . $r->id,
                            // 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->name . '?"',
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(['action', 'nama', 'alamat'])
            ->make(true);
    }

    public function wawancaraGrid(Tad $tad)
    {
        $records = Wawancara::whereHas(
            'kandidat',
            function ($w) use ($tad) {
                $w->where('tad_id', $tad->id);
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
                'no_tiket',
                function ($r) {
                    return '<div>
            <span class="text-bold">No : </span><span>' . $r->kandidat->requirement->pengajuan->no_tiket . '</span><br>
            <span class="text-bold">Tanggal : </span><span>' . $r->created_at->format('d/m/Y') . '</span>
            </div>';
                }
            )
            ->editColumn(
                'kesimpulan',
                function ($r) {
                    return $r->kesimpulanRaw();
                }
            )
            ->editColumn(
                'posisi',
                function ($r) {
                    return '<div>
            <span class="text-bold">Jabatan : </span><span>' . $r->kandidat->requirement->jabatan->NM_UNIT . '</span><br>
            <span class="text-bold">Cabang : </span><span>' . $r->kandidat->requirement->pengajuan->cabang->CAPEM . '</span>
            </div>';
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
                                'url' => route($this->route . '.kandidat.wawancara.show', $r->id),
                            ],
                            // 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->name . '?"',
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(['action', 'posisi', 'kesimpulan', 'no_tiket'])
            ->make(true);
    }

    public function wawancaraShow(Wawancara $wawancara)
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Detail Wawancara TAD',
            ]
        );
        return $this->render(
            $this->view . '.kandidat.wawancara.show',
            [
                'kompetensi' => Kompetensi::orderBy('kompetensi', 'desc')->get(),
                'wawancara' => $wawancara,
                'kandidat' => $wawancara->kandidat->tad,
            ]
        );
    }

    public function kandidatShow(Tad $tad)
    {
        /* Grid Keluarga */
        $this->setPrepare(
            [
                'tableStruct' => [
                    'table_url_1' => route($this->route . '.kandidat.keluarga.grid', $tad->id),
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:nama|label:Nama|sortable:false|className:text-left'),
                        $this->makeColumn('name:alamat|label:Alamat|sortable:false|className:text-left'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false'),
                    ],
                    'table_url_2' => route($this->route . '.kandidat.wawancara.grid', $tad->id),
                    'datatable_2' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:no_tiket|label:No Tiket Pengajuan|sortable:false|className:text-left'),
                        $this->makeColumn('name:posisi|label:Penempatan|sortable:false|className:text-left'),
                        $this->makeColumn('name:kesimpulan|label:Kesimpulan|sortable:false'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false'),
                    ]
                ],
            ]
        );
        return $this->render(
            $this->view . '.kandidat.show',
            [
                'tad' => $tad,
            ]
        );
    }
}
