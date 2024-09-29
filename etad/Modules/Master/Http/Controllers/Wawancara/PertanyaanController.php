<?php

namespace Modules\Master\Http\Controllers\Wawancara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\Wawancara\Pertanyaan;
use Yajra\DataTables\DataTables;

class PertanyaanController extends Controller
{
    protected $module = 'master.wawancara.pertanyaan';
    protected $route  = 'master.wawancara.pertanyaan';
    protected $view   = 'master::wawancara.pertanyaan';
    protected $perms = 'master';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'perms'  => $this->perms,
                'title'  => 'Pertanyaan',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Wawancara' => route($this->route . '.index'),
                    'Pertanyaan' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $order_column = $request->order[0]['column'] ?? 'updated_at';
        $order_dir      = $request->order[0]['dir'] ?? 'asc';
        $records = Pertanyaan::when(
            $keyword = request()->keyword,
            function ($q) use ($keyword) {
                $q->whereLike(['pertanyaan'], $keyword);
            }
        )
            ->when(
                $kompetensi_id = request()->kompetensi_id,
                function ($q) use ($kompetensi_id) {
                    $q->where('kompetensi_id', $kompetensi_id);
                }
            );
        if ($order_column == 1) {
            $records->with(
                [
                    'kompetensi' => function ($q) use ($order_dir) {
                        $q->orderBy('kompetensi', $order_dir);
                    }
                ]
            );
        } else {
            $records->with('kompetensi')
                ->orderBy('ref_wawancara_pertanyaan.updated_at', 'DESC');
        }
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'kompetensi.kompetensi',
                function ($r) {
                    return ($r->kompetensi->kompetensi ?? '');
                }
            )
            ->editColumn(
                'pertanyaan',
                function ($r) {
                    return \Str::words(strip_tags($r->pertanyaan), 20, ' ...');
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
                            'attrs' => 'data-confirm-text="Hapus pertanyaan wawancara ini?"'
                        ];
                    }
                    return $this->makeDropdown($actions);
                }
            )
            ->rawColumns(
                [
                    'kompetensi.kompetensi',
                    'pertanyaan',
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
                        $this->makeColumn('name:pertanyaan|label:Nama|sortable:false|className:text-left|width:600px'),
                        $this->makeColumn('name:kompetensi.kompetensi|label:Kompetensi|sortable:false|className:text-center'),
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
                'kompetensi_id' => ['required', 'exists:ref_wawancara_kompetensi,id'],
                'pertanyaan' => ['required', 'unique:ref_wawancara_pertanyaan,pertanyaan']
            ]
        );
        return Pertanyaan::createByRequest($request);
    }


    public function show(Pertanyaan $pertanyaan)
    {
        return $this->render($this->view . '.show', ['record' => $pertanyaan]);
    }


    public function edit(Pertanyaan $pertanyaan)
    {
        return $this->render($this->view . '.edit', ['record' => $pertanyaan]);
    }


    public function update(Request $request, Pertanyaan $pertanyaan)
    {
        $request->validate(
            [
                'kompetensi_id' => ['required', 'exists:ref_wawancara_kompetensi,id'],
                'pertanyaan' => ['required', 'unique:ref_wawancara_pertanyaan,pertanyaan,' . $pertanyaan->id]
            ]
        );
        return $pertanyaan->updateByRequest($request);
    }


    public function destroy(Pertanyaan $pertanyaan)
    {
        return $pertanyaan->deleteByRequest();
    }
}
