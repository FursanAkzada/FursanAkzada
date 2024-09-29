<?php

namespace Modules\Master\Http\Controllers\SO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\Geografis\City;
use Modules\Master\Entities\Geografis\Province;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\SO\Unit;
use Yajra\DataTables\DataTables;

class RootController extends Controller
{
    protected $module = 'master.so.root';
    protected $route  = 'master.so.root';
    protected $perms  = 'master';
    protected $view   = 'master::so.root';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Perseroan',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Struktur Organisasi' => route($this->route . '.index'),
                    'Perseroan' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user = auth()->user();
        $records = OrgStruct::root()->filters();

        return DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn('name', function ($record) {
                return $record->name;
            })
            ->addColumn('address', function ($record) {
                return $record->address;
            })
            ->editColumn('phone', function ($record) {
                return ($record->phone);
            })
            ->addColumn('province', function ($record) {
                return $record->province ? $record->province->name : '-';
            })
            ->addColumn('city', function ($record) {
                return $record->city ? $record->city->name : '-';
            })
            ->editColumn('created_at', function ($record) {
                return $record->createdByRaw2();
            })
            ->addColumn('action', function ($record) use ($user) {
                $actions = [
                    'type:show|id:' . $record->id,
                    'type:edit|id:' . $record->id,
                ];
                return $this->makeDropdown($actions);
            })
            ->rawColumns(
                [
                    'name',
                    'phone',
                    'action',
                    'status',
                    'created_at',
                    'address'
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
                        $this->makeColumn('name:name|label:Nama|sortable:false'),
                        $this->makeColumn('name:address|label:Alamat|sortable:false'),
                        $this->makeColumn('name:phone|label:Telepon|sortable:false'),
                        // $this->makeColumn('name:email|label:Email|sortable:false'),
                        // $this->makeColumn('name:province|label:Provinsi|sortable:false'),
                        // $this->makeColumn('name:city|label:Kota/Kabupaten|sortable:false'),
                        // $this->makeColumn('name:fax|label:Fax|sortable:false'),
                        // $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|className:align-items-center'),
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
                'name' => ['required', 'unique:ref_unit,name'],
                'mailing' => ['required', 'unique:ref_unit,mailing'],
            ]
        );
        return Unit::createByRequest($request);
    }


    public function show($id)
    {
        $record = OrgStruct::findOrFail($id);
        $province       = Province::get(['id', 'name']);
        $city           = City::get(['id', 'name']);
        return $this->render($this->view . '.show', compact('record', 'province', 'city'));
    }


    public function edit($id)
    {
        $record         = OrgStruct::findOrFail($id);
        $province       = Province::get(['id', 'name']);
        $city           = City::where('province_id', $record->province_id)->get(['id', 'name']);
        return $this->render($this->view . '.edit', compact('record', 'province', 'city'));
    }


    public function update($id, Request $request)
    {
        \DB::beginTransaction();
        try {
            $record = OrgStruct::find($id);
            $old = $record->replicate();
            $record->fill($request->only('name', 'email', 'address', 'province_id', 'city_id', 'phone', 'fax'));
            $record->save();

            auth()->user()->storeLog(
                'master.so.root',
                'update',
                $record->toArray(),
            );
            \DB::commit();
        } catch (\Exception $e) {
            return $this->rollback([
                'errors' => $e->getMessage()
            ]);
        }
    }


    public function destroy(Unit $record)
    {
        return $record->deleteByRequest();
    }

    public function getCity(Request $request)
    {
        $province = $request->province;
        $selected = $request->selected;

        return City::options('name', 'id', [
            'filters' => [
                function ($q) use ($province) {
                    $q->where('province_id', $province);
                }
            ],
            'selected' => $selected
        ]);
    }

    public function getKab(Request $request)
    {
        $province = $request->province;
        $selected = $request->selected;

        return City::options('name', 'id', [
            'filters' => [
                function ($q) use ($province) {
                    $q->where('province_id', $province);
                }
            ],
            'selected' => $selected
        ]);
    }

    public function getKecamatan(Request $request)
    {
        $kab = $request->pelapor_kota;
        $selected = $request->selected;

        return District::options('name', 'id', [
            'filters' => [
                function ($q) use ($kab) {
                    $q->where('city_id', $kab);
                }
            ],
            'selected' => $selected
        ]);
    }
}
