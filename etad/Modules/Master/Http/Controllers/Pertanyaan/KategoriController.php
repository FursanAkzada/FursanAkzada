<?php

namespace Modules\Master\Http\Controllers\Pertanyaan;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Master\Entities\Pertanyaan\Tad;
use Yajra\DataTables\DataTables;

class KategoriController extends Controller
{

    protected $module = 'master.pertanyaan.kategori';
    protected $route  = 'master.pertanyaan.kategori';
    protected $view   = 'master::pertanyaan.kategori';
    protected $perms = 'master';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'perms'  => $this->perms,
                'title'  => 'Kategori Pertanyaan',
                'breadcrumb' => [
                    'Parameter' =>  route($this->route . '.index'),
                    'Pertanyaan' =>  route($this->route . '.index'),
                    'Kategori Pertanyaan' =>  route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $records = Tad::when(
            $keyword = request()->keyword,
            function ($q) use ($keyword) {
                $q->whereLike(['pertanyaan'], $keyword)
                    ->orWhere('judul', 'LIKE', '%' . $keyword . '%');
            }
        )
            ->kategori()
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
                'judul',
                function ($r) {
                    return $r->judul;
                }
            )
            ->editColumn(
                'pertanyaan',
                function ($r) {
                    return $r->child()->count() . ' Pertanyaan';
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
                    // return auth()->user()->checkPerms($this->perms . '.delete') . ' | ' . $this->perms . '.delete';
                    $buttons = $this->makeDropdown(
                        [
                            'type:show|class:base-modal--render|page:true|label:Lihat|id:' . $r->id,
                            'type:edit|class:base-modal--render|page:true|label:Ubah|id:' . $r->id,
                            'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->pertanyaan . '?"|hidden:' . (!auth()->user()->checkPerms($this->perms . '.delete') || $r->child()->count() > 0),
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(
                [
                    'judul',
                    'pertanyaan',
                    'num', 'action', 'created_at'
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
                        $this->makeColumn('name:judul|label:Nama|sortable:false|className:text-center'),
                        $this->makeColumn('name:pertanyaan|label:Nama|sortable:false|className:text-center'),
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
                'judul' => ['required', 'unique:ref_penilaian_tad,judul']
            ]
        );
        return Tad::createByRequest($request);
    }

    public function show(Tad $kategori)
    {
        return $this->render($this->view . '.show', ['record' => $kategori]);
    }

    public function edit(Tad $kategori)
    {
        return $this->render($this->view . '.edit', ['record' => $kategori]);
    }

    public function update(Request $request, Tad $kategori)
    {
        $request->validate(
            [
                'judul' => ['required', 'unique:ref_penilaian_tad,judul,' . $kategori->id]
            ]
        );
        return $kategori->updateByRequest($request);
    }

    public function destroy(Tad $kategori)
    {
        return $kategori->deleteByRequest();
    }
}
