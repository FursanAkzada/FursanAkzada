<?php

namespace Modules\Master\Http\Controllers\Geografis;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Master\Entities\Geografis\Province;
use Yajra\DataTables\DataTables;

class ProvinsiController extends Controller
{
    protected $module = 'master.geografis.provinsi';
    protected $route  = 'master.geografis.provinsi';
    protected $perms  = 'master';
    protected $view   = 'master::geografis.provinsi';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Provinsi',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Geografis' => route($this->route . '.index'),
                    'Provinsi' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $records = Province::withCount('cities')
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike('name', $keyword);
                }
            )
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
                'name',
                function ($r) {
                    return $r->name;
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
                    $buttons = [
                        'type:show|class:base-modal--render|page:true|label:Lihat|id:' . $r->id,
                        'type:edit|id:' . $r->id,
                    ];
                    if (!$r->cities_count) {
                        $buttons[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->name . '?"';
                    }
                    return $this->makeDropdown(
                        $buttons
                    );
                }
            )
            ->rawColumns(['name','action', 'created_at'])
            ->make(true);
    }

    public function index()
    {
        $this->setPrepare(
            [
                // 'perms'
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:name|label:Nama|sortable:false'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
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

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'unique:ref_province,name'],
            ]
        );
        return Province::createByRequest($request);
    }


    public function show(Province $provinsi)
    {
        return $this->render($this->view . '.show',
        [
            'record' => $provinsi
        ]);
    }


    public function edit(Province $provinsi)
    {
        return $this->render(
            $this->view . '.edit',
            [
                'record' => $provinsi
            ]
        );
    }


    public function update(Request $request, Province $provinsi)
    {
        $request->validate(
            [
                'name' => ['required', 'unique:ref_province,name,' . $provinsi->id],
            ]
        );
        return $provinsi->updateByRequest($request);
    }


    public function destroy(Province $provinsi)
    {
        return $provinsi->deleteByRequest();
    }
}
