<?php

namespace Modules\Master\Http\Controllers;

use App\Entities\EHC\Jabatan as Position;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PositionController extends Controller
{
    protected $module = 'master.so.position';
    protected $route  = 'master.so.position';
    protected $view   = 'master::position';
    protected $perms = 'master';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'perms'  => $this->perms,
                'title'  => 'Master Jabatan',
                'breadcrumb' => [
                    'Master' => '/',
                    'Jabatan' => '/',
                ]
            ]
        );
    }

    public function grid()
    {
        $records = Position::when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike(['NM_UNIT', 'idunit'], $keyword);
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
                'created_at',
                function ($r) {
                    return $r->createdByRaw2();
                }
            )
            ->editColumn(
                'action',
                function ($r) {
                    $buttons = $this->makeDropdown(
                        [
                            'type:edit|class:base-modal--render|attrs:data-modal-size="modal-md"|label:Ubah|id:' . $r->idunit,
                            // 'type:show|class:base-modal--render|attrs:data-modal-size="modal-lg"|page:true|label:Detail|id:' . $r->id,
                            'type:delete|id:' . $r->idunit . '|attrs:data-confirm-text="Hapus?"|hidden:' . !auth()->user()->checkPerms($this->perms . '.delete'),
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(['action', 'created_at'])
            ->make(true);
    }

    public function index()
    {
        $this->setPrepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        // $this->makeColumn('name:idunit|label:Kode|sortable:false|className:text-center|width:100px'),
                        $this->makeColumn('name:NM_UNIT|label:Nama|sortable:false|className:text-left|width:400px'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:200px'),
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
                'NM_UNIT' => ['required', 'unique:TBL_UNITKERJA,NM_UNIT']
            ],
            [],
            [
                'NM_UNIT' => 'Nama'
            ]
        );
        return Position::createByRequest($request);
    }

    public function show(Position $position)
    {
        return $this->render($this->view . '.show', ['record' => $position]);
    }

    public function edit(Position $position)
    {
        return $this->render($this->view . '.edit', ['record' => $position]);
    }

    public function update(Request $request, Position $position)
    {
        $request->validate(
            [
                'NM_UNIT' => ['required', 'unique:TBL_UNITKERJA,NM_UNIT']
            ],
            [],
            [
                'NM_UNIT' => 'Nama'
            ]
        );
        return $position->updateByRequest($request);
    }

    public function destroy(Position $position)
    {
        return $position->deleteByRequest();
    }

    public function selectCc(Request $request)
    {
        $records = Position::when(
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
