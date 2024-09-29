<?php

namespace Modules\Master\Http\Controllers\Pertanyaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\Pertanyaan\Tad;
use Yajra\DataTables\DataTables;

class PertanyaanTadController extends Controller
{
    protected $module = 'master.pertanyaan.tad';
    protected $route  = 'master.pertanyaan.tad';
    protected $view   = 'master::pertanyaan.tad';
    protected $perms = 'master';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'perms'  => $this->perms,
                'title'  => 'Pertanyaan TAD',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Pertanyaan' => route($this->route . '.index'),
                    'Pertanyaan TAD' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $records = Tad::when(
            $keyword = request()->keyword,
            function ($q) use ($keyword) {
                $q->whereLike(['pertanyaan'], $keyword);
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
                'pertanyaan',
                function ($r) {
                    if ($r->parent_id > 0) {
                        return '<i class="fas fa-angle-right fa-fw text-dark"></i> ' . $r->judul;
                    } else {
                        return '<b>' . $r->judul . '</b>';
                    }
                }
            )
            ->editColumn(
                'action',
                function ($r) {
                    $buttons = $this->makeDropdown(
                        [

                            'type:show|class:base-modal--render|page:true|label:Lihat|id:' . $r->id,
                            'type:edit|class:base-modal--render|page:true|label:Ubah|id:' . $r->id,
                            'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->pertanyaan . '?"|hidden:' . !auth()->user()->checkPerms($this->perms . '.delete'),
                        ]
                    );
                    return $buttons;
                }
            )
            ->order(
                function ($q) {
                    $q->orderByRaw("CONCAT((CASE WHEN parent_id IS NULL THEN id ELSE parent_id END), '.', urut)");
                }
            )
            ->rawColumns(['action', 'created_at', 'pertanyaan'])
            ->make(true);
    }

    public function index()
    {
        $this->setPrepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:pertanyaan|label:Nama|sortable:false|className:text-left|width:400px'),
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
                'pertanyaan' => ['required', 'unique:ref_penilaian_tad,pertanyaan']
            ]
        );
        return Tad::createByRequest($request);
    }

    public function show(Tad $pertanyaanTad)
    {
        return $this->render($this->view . '.show', ['record' => $pertanyaanTad]);
    }

    public function edit(Tad $pertanyaanTad)
    {
        return $this->render($this->view . '.edit', ['record' => $pertanyaanTad]);
    }

    public function update(Request $request, Tad $pertanyaanTad)
    {
        return $pertanyaanTad->updateByRequest($request);
    }

    public function destroy(Tad $pertanyaanTad)
    {
        // dd(140);
        return $pertanyaanTad;
        return $pertanyaanTad->deleteByRequest();
    }
}
