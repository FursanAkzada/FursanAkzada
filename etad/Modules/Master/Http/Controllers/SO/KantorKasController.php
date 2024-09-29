<?php

namespace Modules\Master\Http\Controllers\SO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\Geografis\City;
use Modules\Master\Entities\Geografis\Province;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Http\Requests\KasRequest;
use Yajra\DataTables\DataTables;

class KantorKasController extends Controller
{
    protected $module = 'master.so.kantor-kas';
    protected $route  = 'master.so.kantor-kas';
    protected $perms  = 'master';
    protected $view   = 'master::so.kantor-kas';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Kantor Kas',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Struktur Organisasi' => route($this->route . '.index'),
                    'Kantor Kas' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $user = auth()->user();
        $records = OrgStruct::kas()
            ->when(
                $name = $request->name,
                function ($q) use ($name) {
                    $q->where(
                        function ($q) use ($name) {
                            $q->where('name', 'LIKE', '%' . $name . '%')
                                ->orWhere('code', 'LIKE', '%' . $name . '%');
                        }
                    );
                }
            )->when(
                $parent_id = $request->parent_id,
                function ($q) use ($parent_id) {
                    $q->where('parent_id', $parent_id);
                }
            )->latest("updated_at");

        return DataTables::of($records)
            ->addColumn('num', function ($record) {
                return request()->start;
            })
            ->addColumn(
                'code',
                function ($record) {
                    return ($record->code);
                }
            )
            ->addColumn(
                'name',
                function ($record) {
                    return ($record->name);
                }
            )
            ->addColumn(
                'parent',
                function ($record) {
                    return ($record->parent ? $record->parent->name : '');
                }
            )
            ->editColumn('created_at', function ($record) {
                return $record->createdByRaw2();
            })
            ->addColumn('action', function ($record) use ($user) {
                $actions = [
                    'type:show|label:Lihat|id:' . $record->id,
                    'type:edit|id:' . $record->id,
                ];

                if ($record->canDeleted()) {
                    $actions[] = [
                        'type'  => 'delete',
                        'id'    => $record->id,
                        'attrs' => 'data-confirm-text="Hapus ' . $record->name . '?"'
                    ];
                }
                return $this->makeDropdown($actions);
            })
            ->rawColumns(
                [
                    'code',
                    'name',
                    'parent',
                    'action', 'status', 'created_at', 'address'
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
                        $this->makeColumn('name:code|label:Kode|sortable:false'),
                        $this->makeColumn('name:name|label:Nama|sortable:false'),
                        $this->makeColumn('name:parent|label:Parent|sortable:false'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );
        $parents        = OrgStruct::whereIn('level', ['cabang'])->get(['id', 'name']);
        return $this->render($this->view . '.index', compact('parents'));
    }


    public function create()
    {
        $parents        = OrgStruct::whereIn('level', ['cabang'])->get(['id', 'name']);
        $province       = Province::get(['id', 'name']);
        $city           = City::get(['id', 'name']);
        return $this->render($this->view . '.create', compact('parents', 'province', 'city'));
    }


    public function store(KasRequest $request)
    {
        \DB::beginTransaction();
        try {
            $record = new OrgStruct;
            $record->level = 'kas';
            $record->fill($request->only('parent_id', 'code', 'name', 'phone', 'address'));
            $record->save();
            $record->generateQuota();

            auth()->user()->storeLog(
                'master.so.kantor-kas',
                'create',
            );
            \DB::commit();
        } catch (\Exception $e) {
            return $this->rollback([
                'errors' => $e->getMessage()
            ]);
        }
    }


    public function show($id)
    {
        //return view('master::show');
        $record         = OrgStruct::findOrFail($id);
        $parents        = OrgStruct::whereIn('level', ['cabang'])->get(['id', 'name']);
        $province       = Province::get(['id', 'name']);
        $city           = City::get(['id', 'name']);
        return $this->render($this->view . '.show', compact('record', 'parents', 'province', 'city'));
    }


    public function edit($id)
    {
        $record         = OrgStruct::findOrFail($id);
        $parents        = OrgStruct::whereIn('level', ['cabang'])->get(['id', 'name']);
        $province       = Province::get(['id', 'name']);
        $city           = City::get(['id', 'name']);
        return $this->render($this->view . '.edit', compact('record', 'parents', 'province', 'city'));
    }


    public function update($id, KasRequest $request)
    {
        \DB::beginTransaction();
        try {
            $record = OrgStruct::find($id);
            $old = $record->replicate();
            $record->fill($request->only('parent_id', 'code', 'name', 'phone', 'address', 'province', 'city'));
            $record->save();

            auth()->user()->storeLog(
                'master.so.kantor-kas',
                'update',
            );

            \DB::commit();
        } catch (\Exception $e) {
            return $this->rollback([
                'errors' => $e->getMessage()
            ]);
        }
    }


    public function destroy($id)
    {
        \DB::beginTransaction();
        try {
            $record = OrgStruct::find($id);

            if ($record->child->count() == 0) {
                $record->delete();
                auth()->user()->storeLog(
                    'master.so.kantor-kas',
                    'delete',
                );
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data successfully deleted',
            'data'    => $this
        ]);
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
