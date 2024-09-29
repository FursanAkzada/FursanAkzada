<?php

namespace Modules\Master\Http\Controllers\Geografis;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Master\Entities\Geografis\City;
use Modules\Master\Entities\Geografis\Province;
use Yajra\DataTables\DataTables;

class KabKotaController extends Controller
{
    protected $module = 'master.geografis.kab-kota';
    protected $route  = 'master.geografis.kab-kota';
    protected $perms  = 'master';
    protected $view   = 'master::geografis.kab-kota';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Kota/Kabupaten',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Geografis' => route($this->route . '.index'),
                    'Kota/Kabupaten' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $records = City::with('province')
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike('name', $keyword);
                }
            )
            ->when(
                $province = request()->province,
                function ($q) use ($province) {
                    $q->where('province_id', $province);
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
                'province.name',
                function ($r) {
                    return ($r->province->name ?? '-');
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
                    $buttons = '';
                    $buttons .= $this->makeDropdown(
                        [
                            'type:show|label:Lihat|id:' . $r['id'],
                            'type:edit|id:' . $r['id'],
                            'type:delete|id:' . $r['id'] . '|attrs:data-confirm-text="Hapus ' . $r['name'] . '?"',
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(
                [
                    'name',
                    'province.name',
                    'action', 'created_at'
                ]
            )
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
                        $this->makeColumn('name:name|label:Nama|sortable:false|className:text-center'),
                        $this->makeColumn('name:province.name|label:Provinsi|sortable:false|className:text-center'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:4px')
                    ]
                ],
            ]
        );
        $province = Province::get();

        return $this->render($this->view . '.index', compact('province'));
    }

    public function create()
    {
        $province = Province::get();

        return $this->render($this->view . '.create', compact('province'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'unique:ref_city,name'],
            ]
        );
        return City::createByRequest($request);
    }


    public function show(City $kab_kotum)
    {
        return $this->render(
            $this->view . '.show',
            [
                'record' => $kab_kotum,
                'province' => Province::get()
            ]
        );
    }

    public function edit(City $kab_kotum)
    {
        return $this->render(
            $this->view . '.edit',
            [
                'record' => $kab_kotum,
                'province' => Province::get()
            ]
        );
    }

    public function update(Request $request, City $kab_kotum)
    {
        $request->validate(
            [
                'province_id' => ['required'],
                'name' => ['required', 'unique:ref_city,name,' . $kab_kotum->id],
            ]
        );
        return $kab_kotum->updateByRequest($request);
    }


    public function destroy(City $kab_kotum)
    {
        return $kab_kotum->deleteByRequest();
    }
}
