<?php

namespace Modules\Master\Http\Controllers\SO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Http\Requests\DepartemenRequest;
use Yajra\DataTables\DataTables;

class DepartemenController extends Controller
{
    protected $module = 'master';
    protected $route  = 'master.so.departemen';
    protected $perms  = 'master';
    protected $view   = 'master::so.departemen';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Sub Divisi',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Struktur Organisasi' => route($this->route . '.index'),
                    'Sub Divisi' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $user = auth()->user();
        $records = OrgStruct::departemen()
            ->when(
                $parent_id = $request->parent_id,
                function ($q) use ($parent_id) {
                    $q->where('parent_id', $parent_id);
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
                        'type:show|id:' . $record->id,
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
                }
            )
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
        $parents = OrgStruct::division()->orderBy('name', 'ASC')->get(['id', 'name']);
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
        return $this->render(
            $this->view . '.index',
            compact('parents')
        );
    }


    public function create()
    {
        $parents = OrgStruct::division()->get(['id', 'name']);
        return $this->render($this->view . '.create', compact('parents'));
    }


    public function store(DepartemenRequest $request)
    {
        \DB::beginTransaction();
        try {
            $record = new OrgStruct;
            $record->level = 'departemen';
            $record->fill($request->only('parent_id', 'code', 'name', 'phone', 'address'));
            $record->save();

            auth()->user()->storeLog(
                'master.so.sub-divisi',
                'create',
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


    public function show($id)
    {
        $record = OrgStruct::findOrFail($id);
        return $this->render($this->view . '.show', compact('record'));
    }


    public function edit($id)
    {
        $record = OrgStruct::findOrFail($id);
        $parents = OrgStruct::where('id', '!=', $record->id)->division()->get(['id', 'name']);
        return $this->render($this->view . '.edit', compact('record', 'parents'));
    }


    public function update($id, DepartemenRequest $request)
    {
        \DB::beginTransaction();
        try {
            $record = OrgStruct::find($id);
            $old = $record->replicate();
            $record->fill($request->only('parent_id', 'code', 'name', 'phone', 'address'));
            $record->save();

            auth()->user()->storeLog(
                'master.so.sub-divisi',
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


    public function destroy($id, Request $request)
    {
        \DB::beginTransaction();
        try {
            $record = OrgStruct::find($id);

            if ($record->child->count() == 0) {
                $record->delete();
                auth()->user()->storeLog(
                    'master.so.sub-divisi',
                    'delete',
                );
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        return response()->json([
            'success' => true,
            'message' => 'Data successfully deleted',
            'data'    => $this
        ]);
    }
}
