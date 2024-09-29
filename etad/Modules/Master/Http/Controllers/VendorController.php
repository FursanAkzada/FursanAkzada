<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\KategoriVendor;
use Modules\Master\Entities\Vendor;
use Yajra\DataTables\DataTables;

class VendorController extends Controller
{
    protected $module = 'master.vendor';
    protected $route  = 'master.vendor';
    protected $view   = 'master::vendor';
    protected $perms  = 'master';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'title'  => 'Vendor',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Vendor.' => route($this->route . '.index'),
                    'Vendor' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function ajax(Request $request)
    {
        return Vendor::when(
            auth()->user()->isVendor,
            function ($q) {
                $q->where('id', auth()->user()->vendor_id);
            }
        )
            ->when(
                $unit_kerja_id = $request->unit_kerja_id,
                function ($q) use ($unit_kerja_id) {
                    $q->where('id', $unit_kerja_id);
                }
            )
            ->orderBy('nama', 'ASC')->get();
    }

    public function ajaxGetByIdUnitKerja(Request $request)
    {
        $records = Vendor::query()
            // ->whereHas('tad', function ($q) {
            //     $q->whereHas('riwayatKerja', function ($qq) {
            //         $qq->where('cabang_id', request()->unit_kerja_id);
            //     });
            // })
            ->when(
                $keyword = request()->q,
                function ($q) use ($keyword) {
                    $q->whereLike(['nama'], $keyword);
                }
            )
            ->when(
                auth()->user()->isVendor,
                function ($q) {
                    $q->where('id', auth()->user()->vendor_id);
                }
            )
            ->orderBy('nama', 'asc')->paginate(100);

        $response = [];
        foreach ($records as $key => $value) {
            $response['results'][] = [
                'id' => $value->id,
                'text' => $value->nama,
            ];
        }
        $response['more'] = $records->hasMorePages();
        return response()->json($response, 200);
    }

    public function selectCc(Request $request)
    {
        $records = Vendor::when(
            $keyword = request()->q,
            function ($q) use ($keyword) {
                $q->whereLike(['nama'], $keyword);
            }
        )
            ->orderBy('nama', 'asc')->paginate(100);

        $response = [];
        foreach ($records as $key => $value) {
            $response['results'][] = [
                'id' => $value->id,
                'text' => $value->nama,
            ];
        }
        $response['more'] = $records->hasMorePages();
        return response()->json($response, 200);
    }

    public function grid()
    {
        $records = Vendor::with(
            [
                'categories' => function ($q) {
                    $q->orderBy('nama', 'ASC');
                }
            ]
        )
            ->where('nama', '!=', 'PLACEHOLDER')
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike(['nama', 'deskripsi'], $keyword);
                }
            )
            ->when(
                $category_id = request()->category_id,
                function ($q) use ($category_id) {
                    $q->whereHas(
                        'categories',
                        function ($q) use ($category_id) {
                            $q->where('kategori_id', $category_id);
                        }
                    );
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
                    return str_word_count($r->deskripsi) . ' Words';
                }
            )
            ->editColumn(
                'is_migrasi',
                function ($r) {
                    if ($r->is_migrasi == 1) {
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
                        'type:show|class:base-modal--render|page:true|label:Lihat|id:' . $r->id,
                        'type:edit|class:base-modal--render|page:true|label:Ubah|id:' . $r->id,
                    ];
                    if (auth()->user()->checkPerms($this->perms . '.delete') && $r->canDelete()) {
                        $action[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->nama . '?"';
                    }
                    return $this->makeDropdown($action);
                }
            )
            ->rawColumns(['nama', 'deskripsi', 'action', 'created_at'])
            ->make(true);
    }

    public function index()
    {
        $CATEGORIES = KategoriVendor::orderBy('nama', 'ASC')->get();
        $this->setPrepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:nama|label:Nama|sortable:true|className:text-center'),
                        $this->makeColumn('name:deskripsi|label:Deskripsi|sortable:false|className:text-center'),
                        // $this->makeColumn('name:is_migrasi|label:Migrasi|sortable:false|className:text-center'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:true|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:50px')
                    ]
                ],
            ]
        );
        return $this->render(
            $this->view . '.index',
            compact('CATEGORIES')
        );
    }

    public function create()
    {
        $KATEGORI = KategoriVendor::where('id', '!=', 1)->orderBy('nama', 'asc')->get();
        return $this->render(
            $this->view . '.create',
            compact('KATEGORI')
        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'nama'          => ['required', 'unique:ref_vendor,nama'],
            ]
        );
        return Vendor::createByRequest($request);
    }

    public function show(Vendor $vendor)
    {
        $record = $vendor;
        $SELECTED_CATEGORIES = $record->categories->pluck('id')->toArray();
        $KATEGORI = KategoriVendor::orderBy('nama', 'asc')->get();
        return $this->render($this->view . '.show', compact('record', 'KATEGORI', 'SELECTED_CATEGORIES'));
    }

    public function edit($id)
    {
        $record = Vendor::with('categories')->find($id);
        $SELECTED_CATEGORIES = $record->categories->pluck('id')->toArray();
        $KATEGORI = KategoriVendor::where('id', '!=', 1)->orderBy('nama', 'asc')->get();
        return $this->render(
            $this->view . '.edit',
            compact('record', 'SELECTED_CATEGORIES', 'KATEGORI')
        );
    }

    public function update(Request $request, Vendor $vendor)
    {
        $request->validate(
            [
                'nama'          => ['required', 'unique:ref_vendor,nama,' . $vendor->id],
            ]
        );
        return $vendor->updateByRequest($request);
    }

    public function destroy(Vendor $vendor)
    {
        return $vendor->deleteByRequest();
    }

    public function selectAjax(Request $request)
    {
        return Vendor::selectAjax($request);
    }

    public function selectAjaxAll(Request $request)
    {
        return Vendor::selectAjaxAll($request);
    }
}
