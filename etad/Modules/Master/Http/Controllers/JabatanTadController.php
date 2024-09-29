<?php

namespace Modules\Master\Http\Controllers;

use App\Entities\EHC\Jabatan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\KategoriVendor;
use Yajra\DataTables\DataTables;

class JabatanTadController extends Controller
{
    protected $module = 'master.jabatan-tad';
    protected $route  = 'master.jabatan-tad';
    protected $view   = 'master::jabatan-tad';
    protected $perms   = 'master';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'title'  => 'Posisi TAD',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Vendor' => route($this->route . '.index'),
                    'Posisi TAD' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function ajax(Request $request)
    {
        return Jabatan::when(
            $kategori_id = $request->kategori_id,
            function ($q) use ($kategori_id) {
                $q->where('kategori_id', $kategori_id);
            }
        )
            ->orderBy('NM_UNIT', 'ASC')->get();
    }

    public function grid(Request $request)
    {
        $records = Jabatan::with('kategori')
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike(['NM_UNIT'], $keyword);
                }
            )
            ->when(
                $kategori_id = $request->kategori_id,
                function ($q) use ($kategori_id) {
                    $q->where('kategori_id', $kategori_id);
                }
            )
            ->when(
                $jenis = $request->jenis,
                function ($q) use ($jenis) {
                    $q->where('jenis', $jenis);
                }
            )
            ->whereHas('kategori')
            ->where('NM_UNIT', '!=', 'PLACEHOLDER J-901')
            ->where('NM_UNIT', '!=', 'PLACEHOLDER J-902')
            ->orderBy('created_at', 'DESC')
            ->orderBy('updated_at', 'DESC');
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'NM_UNIT',
                function ($r) {
                    return $r->NM_UNIT;
                }
            )
            ->editColumn(
                'jenis',
                function ($r) {
                    return ($r->jenis === 'J-901' ? 'Administrasi' : 'Non Administrasi');
                }
            )
            ->editColumn(
                'kategori',
                function ($r) {
                    return ($r->kategori->nama ?? '');
                }
            )
            ->editColumn(
                'is_migrasi',
                function ($r) {
                    if($r->is_migrasi == 1){
                        return 'Ya';
                    }
                    return 'Tidak';
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
                        'type:show|class:base-modal--render|page:true|label:Lihat|id:' . $r->idunit,
                        'type:edit|class:base-modal--render|page:true|label:Ubah|id:' . $r->idunit,
                    ];
                    if (auth()->user()->checkPerms($this->perms . '.delete') && $r->canDelete()) {
                        $action[] = 'type:delete|id:' . $r->idunit . '|attrs:data-confirm-text="Hapus ' . $r->kategori->nama . '?"|hidden:' . !auth()->user()->checkPerms($this->perms . '.delete');
                    }
                    return $this->makeDropdown($action);
                }
            )
            ->rawColumns(
                [
                    'NM_UNIT',
                    'jenis',
                    'kategori',
                    'action', 'created_at'
                ]
            )
            ->make(true);
    }

    public function index()
    {
        $KATEGORI = KategoriVendor::orderBy('nama', 'ASC')->get();
        $this->setPrepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:NM_UNIT|label:Nama|sortable:true|className:text-center'),
                        $this->makeColumn('name:jenis|label:Jenis|sortable:true|className:text-center'),
                        $this->makeColumn('name:kategori|label:Kategori|sortable:false|className:text-center'),
                        // $this->makeColumn('name:is_migrasi|label:Migrasi|sortable:false|className:text-center'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:true|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:50px')
                    ]
                ],
            ]
        );
        return $this->render(
            $this->view . '.index',
            compact('KATEGORI')
        );
    }

    public function create()
    {
        $KATEGORI = KategoriVendor::where('id', '!=', 1)->orderBy('nama', 'ASC')->get();
        return $this->render(
            $this->view . '.create',
            compact('KATEGORI')
        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'kategori_id'   => ['required'],
                'name'          => ['required', 'unique:ref_ehc_unit_kerja,NM_UNIT'],
                'jenis'         => ['required'],
            ]
        );
        return Jabatan::createByRequest($request);
    }

    public function show(Jabatan $jabatan_tad)
    {
        $record = $jabatan_tad;
        $KATEGORI = KategoriVendor::orderBy('nama', 'asc')->get();
        return $this->render($this->view . '.show', compact('record', 'KATEGORI'));
    }

    public function edit($id)
    {
        $record = Jabatan::find($id);
        $KATEGORI = KategoriVendor::where('id', '!=', 1)->orderBy('nama', 'asc')->get();
        return $this->render(
            $this->view . '.edit',
            [
                'record' => $record,
                'KATEGORI' => $KATEGORI
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $record = Jabatan::find($id);
        return $record->updateByRequest($request);
    }

    public function destroy($id)
    {
        $record = Jabatan::find($id);
        return $record->deleteByRequest();
    }
}
