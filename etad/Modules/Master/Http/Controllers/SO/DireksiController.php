<?php

namespace Modules\Master\Http\Controllers\SO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Http\Requests\BodRequest;
use Yajra\DataTables\DataTables;

class DireksiController extends Controller
{
    protected $module = 'master.so.direksi';
    protected $route  = 'master.so.direksi';
    protected $perms  = 'master';
    protected $view   = 'master::so.direksi';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Direksi',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Struktur Organisasi' => route($this->route . '.index'),
                    'Direksi' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user = auth()->user();
        $records = OrgStruct::bod()->filters();

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
                    if ($record->name == "Direktur Utama") {
                        $actions = [
                            'type:show|id:' . $record->id,
                            'type:edit|id:' . $record->id,
                        ];
                    } else {
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
                    }
                    return $this->makeDropdown($actions);
                }
            )
            ->rawColumns(
                [
                    'name',
                    'action',
                    'status',
                    'created_at', 'address', 'parent'
                ]
            )
            ->make(true);
    }

    public function index()
    {
        $parents = OrgStruct::whereIn('level', ['root', 'bod'])->get(['id', 'name']);
        $this->setPrepare(
            [
                // 'perms'
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:name|label:Nama|sortable:false|className:text-center'),
                        $this->makeColumn('name:parent|label:Parent|sortable:false'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:50px')
                    ]
                ],
            ]
        );
        return $this->render($this->view . '.index', compact('parents'));
    }

    public function create()
    {
        $parents = OrgStruct::whereIn('level', ['root', 'bod'])->get(['id', 'name']);
        return $this->render($this->view . '.create', compact('parents'));
    }

    public function store(BodRequest $request)
    {
        \DB::beginTransaction();
        try {
            $record = new OrgStruct;
            $record->level = 'bod';
            $record->fill($request->only('parent_id', 'code', 'name', 'phone', 'address'));
            $record->save();
            $record->generateQuota();

            auth()->user()->storeLog(
                'master.so.direksi',
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
        $parents = OrgStruct::where('id', '!=', $record->id)
            ->whereIn('level', ['root', 'bod'])->get(['id', 'name']);
        return $this->render($this->view . '.edit', compact('record', 'parents'));
    }


    public function update($id, BodRequest $request)
    {
        \DB::beginTransaction();
        try {
            $record = OrgStruct::find($id);
            $old = $record->replicate();
            $record->fill($request->only('parent_id', 'code', 'name', 'phone', 'address'));
            $record->save();


            auth()->user()->storeLog(
                'master.so.direksi',
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
                    'master.so.direksi',
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
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfully deleted',
                'data'    => $this
            ]
        );
    }
    public function cekLevelTipeStruktur(Request $request)
    {
        $so_id      = $request->so_id;

        return response()->json(
            OrgStruct::find($so_id)
        );
    }
}
