<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\KategoriVendor;
use Yajra\DataTables\DataTables;

class KategoriVendorController extends Controller
{
    protected $module = 'master.kategori-vendor';
    protected $route  = 'master.kategori-vendor';
    protected $view   = 'master::kategori-vendor';
    protected $perms   = 'master';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'title'  => 'Kategori TAD',
                'breadcrumb' => [
                    'Parameter' => '/',
                    'Vendor' => url('master/vendor'),
                    'Kategori TAD' => url('master/kategori-vendor'),
                ]
            ]
        );
    }

    public function grid()
    {
        $records = KategoriVendor::where('nama', '!=', 'PLACEHOLDER')
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike(['nama', 'deskripsi'], $keyword);
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
                'nama',
                function ($r) {
                    return $r->nama;
                }
            )
            ->editColumn(
                'deskripsi',
                function ($r) {
                    return $r->deskripsi;
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
                        'type:show|class:base-modal--render|page:true|label:Lihat|id:' . $r->id,
                        'type:edit|class:base-modal--render|page:true|label:Ubah|id:' . $r->id,
                    ];
                    $have_delete_perm = auth()->user()->checkPerms($this->perms . '.delete');
                    $can_delete = $r->canDelete() && $have_delete_perm;
                    // return [$r->canDelete(), $have_delete_perm];
                    if ($can_delete) {
                        $action[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->nama . '?"';
                    }
                    return $this->makeDropdown($action);
                }
            )
            ->rawColumns(
                [
                    'nama',
                    'deskripsi',
                    'action', 'created_at'
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
                        $this->makeColumn('name:nama|label:Nama|sortable:true|className:text-center'),
                        $this->makeColumn('name:deskripsi|label:Deskripsi|sortable:true|className:text-center'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:true|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:50px')
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


    public function store(Request $request)
    {
        $request->validate(
            [
                'nama' => ['required', 'unique:ref_vendor,nama']
            ]
        );
        return KategoriVendor::createByRequest($request);
    }


    public function show(KategoriVendor $kategori_vendor)
    {
        return $this->render($this->view . '.show', ['record' => $kategori_vendor]);
    }


    public function edit($id)
    {
        $record = KategoriVendor::find($id);
        return $this->render($this->view . '.edit', ['record' => $record]);
    }


    public function update(Request $request, $id)
    {
        $record = KategoriVendor::find($id);
        return $record->updateByRequest($request);
    }


    public function destroy($id)
    {
        $record = KategoriVendor::find($id);
        // return $record;
        return $record->deleteByRequest();
    }
}
