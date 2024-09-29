<?php

namespace Modules\Master\Http\Controllers\Tad;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Master\Entities\Kota;
use Modules\Master\Entities\Tad\Keluarga;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Wawancara\Kompetensi;
use Modules\Master\Http\Requests\Tad\KeluargaRequest;
use Modules\Master\Http\Requests\Tad\TadRequest;
use Modules\Pengajuan\Entities\Tad\Wawancara\Wawancara;
use Yajra\DataTables\DataTables;

class TadController extends Controller
{
    protected $module = 'master';
    protected $route  = 'master.tad';
    protected $view   = 'master::tad';
    protected $perms = 'master';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module'     => $this->module,
                'route'      => $this->route,
                'view'       => $this->view,
                'perms' => $this->perms,
                'title'      => 'Data Tenaga Ahli Daya',
                'breadcrumb' => [
                    'Data Tenaga Ahli Daya' => '/',
                ]
            ]
        );
    }

    public function grid()
    {
        /* For Vendor */
        if (auth()->user()->user_type == 'web') {
            return $this->vendorGrid();
        }
        return $this->cabGrid();
    }

    public function _cabGrid()
    {
        $records = Tad::filters()->filterPegawai();

        return DataTables::of($records)
            ->order(
                function ($query) {
                    $query->when(!request()->has('order'), function ($q) {
                        $q->orderBy('nama', 'asc');
                    });
                }
            )
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'jabatan',
                function ($r) {
                    return $r->jabatan->NM_UNIT ?? '-';
                }
            )
            ->editColumn(
                'vendor',
                function ($r) {
                    return $r->vendor->nama ?? '';
                }
            )
            ->editColumn(
                'nama',
                function ($r) {
                    return $this->render($this->view . '.partials.grid.nama', ['r' => $r]);
                }
            )
            ->editColumn(
                'status_pekerja',
                function ($r) {
                    return $this->render($this->view . '.partials.grid.status-pekerja', ['r' => $r]);
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
                    $buttons = $this->makeDropdown(
                        [
                            // 'type:custom|icon:fas fa-users text-primary|page:true|label:Ubah Keluarga|url:tad/keluarga/' . $r->id,
                            // 'type:edit|page:true|label:Ubah TAD|id:' . $r->id,
                            'type:show|page:true|label:Detail|id:' . $r->id,
                            // 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->nama . '?"|hidden:' . !auth()->user()->checkPerms($this->perms . '.delete'),
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(['action', 'created_at', 'nama'])
            ->make(true);
    }

    public function _vendorGrid()
    {
        $records = Tad::filters()->filterPegawai();

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
                'jabatan',
                function ($r) {
                    return $r->jabatan->NM_UNIT ?? '-';
                }
            )
            ->editColumn(
                'vendor',
                function ($r) {
                    return $r->vendor->nama ?? '';
                }
            )
            ->editColumn(
                'status_pekerja',
                function ($r) {
                    return $this->render($this->view . '.partials.grid.status-pekerja', ['r' => $r]);
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
                    $buttons = $this->makeDropdown(
                        [
                            'type:custom|icon:fas fa-users text-primary|page:true|label:Ubah Keluarga|url:tad/keluarga/' . $r->id,
                            'type:edit|page:true|label:Ubah|id:' . $r->id,
                            'type:show|page:true|label:Detail|id:' . $r->id,
                            'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->nama . '?"|hidden:' . (!auth()->user()->checkPerms($this->perms . '.delete') || $r->kepegawaian),
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(['action', 'created_at', 'nama'])
            ->make(true);
    }

    public function index()
    {
        $column = [
            'datatable_1' => [
                $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                $this->makeColumn('name:nama|label:Nama|sortable:false|className:text-left'),
                $this->makeColumn('name:jenis_kelamin|label:Jenis Kelamin|sortable:false|className:text-center'),
                $this->makeColumn('name:jabatan|label:Jabatan|sortable:false|className:text-center'),
                $this->makeColumn('name:vendor|label:Vendor|sortable:false|className:text-center'),
                $this->makeColumn('name:status_pekerja|label:Status Pekerja|sortable:false|className:text-left'),
                $this->makeColumn('name:created_at|label:Diperbarui|sortable:false'),
                $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
            ]
        ];

        /* For Vendor */
        if (auth()->user()->user_type == 'web') {
            $column = [
                'datatable_1' => [
                    $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                    // $this->makeColumn('name:nik|label:NIK|sortable:false|className:text-left'),
                    $this->makeColumn('name:nama|label:Nama|sortable:false|className:text-left'),
                    $this->makeColumn('name:jenis_kelamin|label:Jenis Kelamin|sortable:false|className:text-center'),
                    $this->makeColumn('name:jabatan|label:Jabatan|sortable:false|className:text-center'),
                    $this->makeColumn('name:vendor|label:Vendor|sortable:false|className:text-center'),
                    $this->makeColumn('name:status_pekerja|label:Status Pekerja|sortable:false|className:text-left'),
                    $this->makeColumn('name:created_at|label:Diperbarui|sortable:false'),
                    $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                ]
            ];
        }

        $this->setPrepare([
            // 'perms' => $this->perms.'.index',
            'tableStruct' => $column,
        ]);
        return $this->render($this->view . '.index');
    }

    public function create()
    {
        return $this->render($this->view . '.create');
    }


    public function store(TadRequest $request)
    {
        return Tad::createByRequest($request);
    }


    public function show(Tad $tad)
    {
        $this->setPrepare([
            'tableStruct' => [
                'table_url_1' => route($this->route . '.keluarga.grid', $tad->id),
                'datatable_1' => [
                    $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                    $this->makeColumn('name:nama|label:Nama|sortable:false|className:text-left'),
                    $this->makeColumn('name:alamat|label:Alamat|sortable:false|className:text-left'),
                    $this->makeColumn('name:action|label:Opsi|sortable:false'),
                ],
                'table_url_2' => route($this->route . '.wawancara.grid', $tad->id),
                'datatable_2' => [
                    $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                    $this->makeColumn('name:no_tiket|label:No Tiket Pengajuan|sortable:false|className:text-left'),
                    $this->makeColumn('name:posisi|label:Penempatan|sortable:false|className:text-left'),
                    $this->makeColumn('name:kesimpulan|label:Kesimpulan|sortable:false'),
                    $this->makeColumn('name:action|label:Opsi|sortable:false'),
                ]
            ]
        ]);
        return $this->render($this->view . '.show', ['record' => $tad]);
    }


    public function edit(Tad $tad)
    {
        return $this->render($this->view . '.edit', [
            'record' => $tad
        ]);
    }


    public function update(Tad $tad, TadRequest $request)
    {
        return $tad->updateByRequest($request);
    }


    public function destroy(Tad $tad)
    {
        return $tad->deleteByRequest();
    }

    public function keluargaGrid(Tad $tad)
    {
        $records = $tad->keluarga;
        return DataTables::of($records)
            ->editColumn('num', function ($r) {
                return request()->start;
            })
            ->editColumn('nama', function ($r) {
                return '<div>
                <span>' . $r->nama . '</span><br>
                <span class="text-bold">Sebagai </span><span> : ' . $r->tipeKeluarga->tipe . '</span>
                </div>';
            })
            ->editColumn('ttl', function ($r) {
                return $r->tempat_lahir . ', ' . $r->tanggal_lahir->format('d/m/Y');
            })
            ->editColumn('alamat', function ($r) {
                return nl2br($r->tempat_lahir) . '<br>
                <span class="text-bold">Telp : </span><span>' . $r->telepon . '</span>';
            })
            ->editColumn('action', function ($r) {
                $buttons = $this->makeDropdown([
                    'type:custom|page:true|icon:fas fa-eye text-primary|label:Detail|page:true|url:kandidat/' . $r->id . '/show',
                    // 'type:edit|id:' . $r->id,
                    // 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->name . '?"',
                ]);
                return $buttons;
            })
            ->rawColumns(['action', 'nama', 'alamat'])
            ->make(true);
    }

    public function keluarga(Tad $tad)
    {
        $this->setPrepare([
            'module' => $this->module,
            'route'  => $this->route,
            'title'  => 'Data Tenaga Ahli Daya',
            'breadcrumb' => [
                'Data Tenaga Ahli Daya' => '/',
                'Data Keluarga' => '/',
            ]
        ]);

        $view = count($tad->keluarga) > 0 ? '.keluarga.edit' : '.keluarga.create';
        return $this->render($this->view . $view, [
            'record' => $tad
        ]);
    }

    public function keluargaStore(KeluargaRequest $request, Tad $tad)
    {
        return $tad->createKeluargaByRequest($request);
    }

    public function keluargaUpdate(KeluargaRequest $request, Tad $tad)
    {
        return $tad->updateKeluargaByRequest($request);
    }

    public function keluargaDestroy(Keluarga $keluarga)
    {
        return $keluarga->deleteByRequest();
    }

    /* Wawancara */
    public function wawancaraGrid(Tad $tad)
    {
        $records = Wawancara::whereHas('kandidat', function ($w) use ($tad) {
            $w->where('tad_id', $tad->id);
        });
        return DataTables::of($records)
            ->editColumn('num', function ($r) {
                return request()->start;
            })
            ->editColumn('no_tiket', function ($r) {
                return '<div>
            <span class="text-bold">No : </span><span>' . $r->kandidat->requirement->pengajuan->no_tiket . '</span><br>
            <span class="text-bold">Tanggal : </span><span>' . $r->created_at->format('d/m/Y') . '</span>
            </div>';
            })
            ->editColumn('kesimpulan', function ($r) {
                return $r->kesimpulanRaw();
            })
            ->editColumn('posisi', function ($r) {
                return '<div>
            <span class="text-bold">Jabatan : </span><span>' . ($r->kandidat->requirement->jabatan->NM_UNIT ?? '-') . '</span><br>
            <span class="text-bold">Cabang : </span><span>' . ($r->kandidat->requirement->pengajuan->cabang->CAPEM ?? '-') . '</span>
            </div>';
            })
            ->editColumn('action', function ($r) {
                $buttons = $this->makeDropdown([
                    [
                        'type' => 'custom',
                        'icon' => 'fas fa-eye text-primary',
                        'label' => 'Detail',
                        'attrs' => 'data-modal-size="modal-xl"',
                        'url' => route($this->route . '.wawancara.show', $r->id),
                    ],
                    // 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->name . '?"',
                ]);
                return $buttons;
            })
            ->rawColumns(['action', 'posisi', 'kesimpulan', 'no_tiket'])
            ->make(true);
    }
    public function wawancaraShow(Wawancara $wawancara)
    {
        $this->setPrepare([
            'module' => $this->module,
            'route'  => $this->route,
            'title'  => 'Detail Wawancara TAD',
            'breadcrumb' => [
                'Pengajuan TAD' => '/',
                'Otorisasi Human Captial' => '/',
                'Wawancara TAD' => '/',
            ]
        ]);
        return $this->render($this->view . '.wawancara.show', [
            'kompetensi' => Kompetensi::orderBy('kompetensi', 'desc')->get(),
            'wawancara' => $wawancara,
        ]);
    }

    /* Helpers */
    public function kota(Request $request)
    {
        $request->validate([
            'provinsi_id' => ['required', 'exists:sys_kota,provinsi_id']
        ]);
        return Kota::where(['provinsi_id' => $request->provinsi_id])->get();
    }
}
