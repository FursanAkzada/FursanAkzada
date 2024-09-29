<?php

namespace Modules\Master\Http\Controllers\Resign;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\Resign\Reason;
use Modules\Master\Http\Requests\ReasonResignRequest;
use Yajra\DataTables\DataTables;

class ReasonResignController extends Controller
{
    protected $module = 'master.reason-resign';
    protected $route  = 'master.reason-resign';
    protected $view   = 'master::reason-resign';
    protected $perms   = 'master';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'title'  => 'Alasan Resign',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Resign & Mutasi' => route($this->route . '.index'),
                    'Alasan Resign' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $records = Reason::when(
            $keyword = request()->keyword,
            function ($q) use ($keyword) {
                $q->whereLike(['alasan'], $keyword);
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
                'alasan',
                function ($r) {
                    return $r->alasan;
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
                function ($r) {
                    $action = [
                        'type:show|label:Lihat|id:' . $r->id,
                        'type:edit|label:Ubah|id:' . $r->id,
                    ];
                    if (auth()->user()->checkPerms($this->perms . '.delete') && $r->canDelete()) {
                        $action[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->nama . '?"';
                    }
                    return $this->makeDropdown($action);
                }
            )
            ->rawColumns(
                [
                    'alasan',
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
                        $this->makeColumn('name:alasan|label:Nama|sortable:false|className:text-center'),
                        $this->makeColumn('name:description|label:Deskripsi|sortable:false|className:text-center'),
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

    public function store(ReasonResignRequest $request)
    {
        $request->validate([
            'alasan' => 'required|unique:ref_alasan_resign,alasan',
        ]);
        $record = new Reason;
        return $record->handleCreateByRequest($request);
    }

    public function show($id)
    {
        $record = Reason::find($id);
        return $this->render($this->view . '.show', ['record' => $record]);
    }

    public function edit($id)
    {
        $record = Reason::find($id);
        return $this->render($this->view . '.edit', ['record' => $record]);
    }

    public function update(ReasonResignRequest $request, $id)
    {
        $request->validate([
            'alasan' => 'required|unique:ref_alasan_resign,alasan,' . $id,
        ]);
        $record = Reason::find($id);
        return $record->handleUpdateByRequest($request);
    }

    public function destroy($id)
    {
        $record = Reason::find($id);
        // return $record;
        return $record->deleteByRequest();
    }

    public function selectAjax(Request $request)
    {
        return Reason::selectAjax();
    }
}
