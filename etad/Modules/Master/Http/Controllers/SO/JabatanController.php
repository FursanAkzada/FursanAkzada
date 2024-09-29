<?php

namespace Modules\Master\Http\Controllers\SO;

use App\Entities\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\SO\Positions;
use Modules\Master\Http\Requests\JabatanRequest;
use Yajra\DataTables\DataTables;

class JabatanController extends Controller
{
    protected $module = 'master';
    protected $route  = 'master.so.jabatan';
    protected $perms  = 'master';
    protected $view   = 'master::so.jabatan';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Jabatan',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Struktur Organisasi' => route($this->route . '.index'),
                    'Jabatan' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $user = auth()->user();
        $records = Positions::when(
            $level = $request->level,
            function ($q) use ($level) {
                $q->whereHas(
                    'struct',
                    function ($q) use ($level) {
                        $q->where('level', $level);
                    }
                );
            }
        )
            ->filters();

        return DataTables::of($records)
            ->addColumn(
                'num',
                function ($record) {
                    return request()->start;
                }
            )
            ->addColumn(
                'name',
                function ($record) {
                    return $record->name;
                }
            )
            ->addColumn(
                'lokasi',
                function ($record) {
                    return ($record->struct ? $record->struct->name : '');
                }
            )
            ->addColumn(
                'parent',
                function ($record) {
                    return $record->parentPosition ? $record->parentPosition->name : '';
                }
            )
            ->editColumn(
                'created_at',
                function ($record) {
                    return $record->createdByRaw2();
                }
            )
            ->addColumn(
                'action',
                function ($record) use ($user) {
                    $actions = [
                        'type:show|label:Lihat|id:' . $record->id,
                        'type:edit|id:' . $record->id,
                    ];

                    if ($record->users->count() == 0) {
                        if (!$record->parentPosition()->has('users')->exists() || $record->childPosition()->count()) {
                            // if (!$record->parent()->has('user')->exists() || $record->childPosition()->count()) {
                            $actions[] = [
                                'type'  => 'delete',
                                'id'    => $record->id,
                                'attrs' => 'data-confirm-text="Hapus ' . $record->name . '?"'
                            ];
                        }
                    }
                    return $this->makeDropdown($actions);
                }
            )
            ->rawColumns(
                [
                    'name',
                    'lokasi',
                    'action', 'status', 'created_at'
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
                        $this->makeColumn('name:lokasi|label:Struktur|sortable:false|className:text-center'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width: 40px')
                    ]
                ],
            ]
        );
        $posisi = Positions::get();
        $struct = OrgStruct::groupByLevel();
        return $this->render($this->view . '.index', compact('posisi', 'struct'));
    }

    public function create()
    {
        $struct = OrgStruct::groupByLevel();

        return $this->render($this->view . '.create', compact('struct'));
    }

    public function store(JabatanRequest $request)
    {
        \DB::beginTransaction();
        try {
            $record = new Positions;
            $record->type = 'jabatan';
            $record->org_struct_id = $request->org_struct_id;
            $record->name = $request->name;
            $record->name_up = ucwords($request->name);
            $record->save();

            auth()->user()->storeLog(
                'master.so.position',
                'create',
                $record->toArray(),
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
        $record = Positions::findOrFail($id);
        $struct = OrgStruct::groupByLevel();
        $parent = Positions::whereNotIn('id', [$record->id])
            ->where('org_struct_id', $record->org_struct_id)
            ->get();
        return $this->render($this->view . '.show', compact('record', 'struct', 'parent'));
    }

    public function edit($id)
    {
        $record = Positions::findOrFail($id);
        $struct = OrgStruct::groupByLevel();
        $parent = Positions::whereNotIn('id', [$record->id])
            ->where('org_struct_id', $record->org_struct_id)
            ->get();
        return $this->render($this->view . '.edit', compact('record', 'struct', 'parent'));
    }

    public function update($id, JabatanRequest $request)
    {
        \DB::beginTransaction();
        try {
            $record = Positions::find($id);
            $record->org_struct_id = $request->org_struct_id;
            $record->parent_id = $request->parent_id;
            // $record->kategori_id = $request->kategori_id;
            $record->name = $request->name;
            $record->name_up = ucwords($request->name);
            $record->save();

            auth()->user()->storeLog(
                'master.so.position',
                'update',
                $record->toArray(),
            );

            \DB::commit();
        } catch (\Exception $e) {
            return $this->rollback(
                [
                    'errors' => $e->getMessage()
                ]
            );
        }
    }

    public function destroy($id)
    {
        \DB::beginTransaction();
        try {
            auth()->user()->storeLog(
                'master.so.position',
                'delete',
            );
            $record = Positions::find($id);
            $record->delete();

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

    public function getParentByLocation($id)
    {
        $list = Positions::where('org_struct_id', $id)->get();

        return response($list);
    }

    public function selectCc(Request $request)
    {
        $records = Positions::whereHas('struct', function ($q) {
                $q->whereIn('level', ['bod', 'vice']);
            })
            ->when(
                $keyword = request()->q,
                function ($q) use ($keyword) {
                    $q->whereLike(['name'], $keyword);
                }
            )
            ->orderBy('name', 'asc')->paginate(100);

        $response = [];
        foreach ($records as $key => $value) {
            $response['results'][] = [
                'id' => $value->id,
                'text' => $value->name,
            ];
        }
        $response['more'] = $records->hasMorePages();
        return response()->json($response, 200);
    }
}
