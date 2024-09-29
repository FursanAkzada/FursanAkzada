<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\Jurusan;
use Modules\Master\Entities\Pendidikan;
use Yajra\DataTables\DataTables;

class JurusanController extends Controller
{
    protected $module = 'master.jurusan';
    protected $route  = 'master.jurusan';
    protected $view   = 'master::jurusan';
    protected $perms = 'master';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'perms'  => $this->perms,
                'title'  => 'Jurusan',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Jurusan' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $order_column = $request->order[0]['column'] ?? 'updated_at';
        $order_dir      = $request->order[0]['dir'] ?? 'asc';
        $records = Jurusan::when(
            $keyword = request()->keyword,
            function ($q) use ($keyword) {
                $q->whereLike(['name'], $keyword);
            }
        )
            ->when(
                $pendidikan_id = request()->pendidikan_id,
                function ($q) use ($pendidikan_id) {
                    $q->where('pendidikan_id', $pendidikan_id);
                }
            );
        if ($order_column == 1) {
            $records->with(
                [
                    'pendidikan' => function ($q) use ($order_dir) {
                        $q->orderBy('pendidikan', $order_dir);
                    }
                ]
            );
        } else {
            $records->with('pendidikan')
                ->orderBy('ref_jurusan.updated_at', 'DESC');
        }
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'pendidikan.name',
                function ($r) {
                    return ($r->pendidikan->name ?? '');
                }
            )
            ->editColumn(
                'name',
                function ($r) {
                    return \Str::words(strip_tags($r->name), 20, ' ...');
                }
            )
            ->editColumn(
                'description',
                function ($r) {
                    return str_word_count($r->description) . ' Words' ;
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
                    'pendidikan.name',
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
                        $this->makeColumn('name:pendidikan.name|label:Pendidikan|sortable:false|className:text-center|width:300px'),
                        // $this->makeColumn('name:description|label:Description|className:text-center|width:150px'),
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
        $PENDIDIKAN = Pendidikan::orderBy('name', 'asc')->get();
        return $this->render($this->view . '.create', compact('PENDIDIKAN'));
    }


    public function store(Request $request)
    {
        $request->validate(
            [
                'pendidikan_id' => ['required', 'exists:ref_pendidikan,id'],
                'name' => ['required', 'unique:ref_jurusan,name']
            ]
        );
        return Jurusan::createByRequest($request);
    }


    public function show(Jurusan $jurusan)
    {
        return $this->render($this->view . '.show', ['record' => $jurusan]);
    }


    public function edit(Jurusan $jurusan)
    {
        $PENDIDIKAN = Pendidikan::orderBy('name', 'asc')->get();
        return $this->render($this->view . '.edit',[
            'record' => $jurusan,
            'PENDIDIKAN' => $PENDIDIKAN,
        ]);
    }


    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate(
            [
                'pendidikan_id' => ['required', 'exists:ref_pendidikan,id'],
                'name' => ['required', 'unique:ref_jurusan,name,' . $jurusan->id]
            ]
        );
        return $jurusan->updateByRequest($request);
    }


    public function destroy(Jurusan $jurusan)
    {
        return $jurusan->deleteByRequest();
    }
}
