<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\Pendidikan;
use Yajra\DataTables\DataTables;

class PendidikanController extends Controller
{
    protected $module = 'master.pendidikan';
    protected $route  = 'master.pendidikan';
    protected $view   = 'master::pendidikan';
    protected $perms = 'master';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'perms'  => $this->perms,
                'title'  => 'Pendidikan',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Pendidikan' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $order_column = $request->order[0]['column'] ?? 'updated_at';
        $order_dir      = $request->order[0]['dir'] ?? 'asc';
        $records = Pendidikan::when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike(['name'], $keyword);
                }
            );
        $records->orderBy('updated_at', 'DESC');
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
                'description',
                function ($r) {
                    return $r->description;
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
                function ($record) {
                    $actions = [
                        'type:show|id:' . $record->id,
                        'type:edit|id:' . $record->id,
                    ];
                    if($record->canDeleted()){
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
                    'name',
                    'description',
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
                        $this->makeColumn('name:name|label:Nama|sortable:false|className:text-center'),
                        $this->makeColumn('name:description|label:Deskripsi|sortable:false|className:text-center|width:200px'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
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
                'name' => ['required', 'unique:ref_pendidikan,name'],
            ]
        );
        return Pendidikan::createByRequest($request);
    }


    public function show(Pendidikan $pendidikan)
    {
        return $this->render($this->view . '.show', ['record' => $pendidikan]);
    }


    public function edit(Pendidikan $pendidikan)
    {
        return $this->render($this->view . '.edit', ['record' => $pendidikan]);
    }


    public function update(Request $request, Pendidikan $pendidikan)
    {
        $request->validate(
            [
                'name' => ['required', 'unique:ref_pendidikan,name,' . $pendidikan->id],
            ]
        );
        return $pendidikan->updateByRequest($request);
    }


    public function destroy(Pendidikan $pendidikan)
    {
        return $pendidikan->deleteByRequest();
    }
}
