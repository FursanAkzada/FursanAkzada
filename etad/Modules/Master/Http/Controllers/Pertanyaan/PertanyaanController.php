<?php

namespace Modules\Master\Http\Controllers\Pertanyaan;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Master\Entities\Pertanyaan\Tad;
use Yajra\DataTables\DataTables;

class PertanyaanController extends Controller
{

    protected $module = 'master';
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
            ->pertanyaan()
            ->when(
                $category_id = request()->category_id,
                function ($q) use ($category_id) {
                    $q->where('parent_id', $category_id);
                }
            )
            ->orderBy('updated_at', 'DESC');

        return DataTables::of($records)
            ->order(
                function ($q) {
                    $q->orderBy('created_at', 'desc');
                }
            )
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'kategori',
                function ($r) {
                    return $r->parent->judul;
                }
            )
            ->editColumn(
                'pertanyaan',
                function ($r) {
                    return '<span class="text-bold">' . $r->judul . '</span><br>' .
                        '<span>' . $r->pertanyaan . '</span>';
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
                            'type:show|class:base-modal--render|page:true|label:Lihat|id:' . $r->id,
                            'type:edit|class:base-modal--render|page:true|label:Ubah|id:' . $r->id,
                            'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->pertanyaan . '?"|hidden:' . !auth()->user()->checkPerms($this->perms . '.delete'),
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(
                [
                    'kategori',
                    'num', 'action', 'created_at', 'pertanyaan'
                ]
            )
            ->make(true);
    }

    public function index()
    {
        $CATEGORIES = Tad::kategori()->get();
        $this->setPrepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:kategori|label:Kategori|sortable:false|className:text-center'),
                        $this->makeColumn('name:pertanyaan|label:Nama|sortable:false|className:text-left'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:50px')
                    ]
                ],
            ]
        );
        return $this->render(
            $this->view . '.index',
            compact('CATEGORIES')
        );
    }

    public function create()
    {
        return $this->render($this->view . '.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'parent_id'  => ['required', 'exists:ref_penilaian_tad,id'],
                'judul'      => ['required', 'unique:ref_penilaian_tad,judul'],
                'pertanyaan' => ['required'],
            ]
        );
        return Tad::createByRequest($request, 'master.pertanyaan.tad');
    }

    public function show(Tad $tad)
    {
        return $this->render($this->view . '.show', ['record' => $tad]);
    }

    public function edit($id)
    {
        $record = Tad::find($id);
        return $this->render($this->view . '.edit', ['record' => $record]);
    }

    public function update(Request $request, $id)
    {
        $record = Tad::find($id);
        $request->validate(
            [
                'parent_id'  => ['required', 'exists:ref_penilaian_tad,id'],
                'judul' => ['required', 'unique:ref_penilaian_tad,judul,' . $record->id],
                'pertanyaan' => ['required'],
            ]
        );
        return $record->updateByRequest($request, 'master.pertanyaan.tad');
    }

    public function destroy(Tad $tad)
    {
        return $tad->deleteByRequest('master.pertanyaan.tad');
    }
}
