<?php

namespace Modules\Master\Http\Controllers\Wawancara;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\Wawancara\Kompetensi;
use Yajra\DataTables\DataTables;

class KompetensiController extends Controller
{
    protected $module = 'master.wawancara.kompetensi';
    protected $route  = 'master.wawancara.kompetensi';
    protected $view   = 'master::wawancara.kompetensi';
    protected $perms = 'master';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'perms'  => $this->perms,
                'title'  => 'Kompetensi',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Wawancara' => route($this->route . '.index'),
                    'Kompetensi' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $order_column = $request->order[0]['column'] ?? 'updated_at';
        $order_dir      = $request->order[0]['dir'] ?? 'asc';
        $records = Kompetensi::withCount('pertanyaan')
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike(['kompetensi', 'uraian'], $keyword);
                }
            );
        if ($order_column == 3) {
            $records->withCount(
                [
                    'pertanyaan'
                ]
            );
        } else {
            $records->orderBy('updated_at', 'DESC');
        }
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'kompetensi',
                function ($r) {
                    return $r->kompetensi;
                }
            )
            ->editColumn(
                'uraian',
                function ($r) {
                    return \Str::words(strip_tags($r->uraian), 20, ' ...');
                }
            )
            ->editColumn(
                'pertanyaan_count',
                function ($r) {
                    return $r->pertanyaan_count . ' Pertanyaan';
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
                            'type:show|class:base-modal--render|attrs:data-modal-size="modal-lg"|page:true|label:Lihat|id:' . $r->id,
                            'type:edit|class:base-modal--render|attrs:data-modal-size="modal-lg"|page:true|label:Ubah|id:' . $r->id,
                            'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus Kompetensi?"|hidden:' . (!auth()->user()->checkPerms($this->perms . '.delete') || $r->pertanyaan_count > 0),
                        ]
                    );
                    return $buttons;
                }
            )
            ->rawColumns(
                [
                    'kompetensi',
                    'uraian',
                    'pertanyaan_count',
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
                        $this->makeColumn('name:kompetensi|label:Nama|sortable:false|className:text-center'),
                        $this->makeColumn('name:uraian|label:Uraian|sortable:false|className:text-left|width:550px'),
                        $this->makeColumn('name:pertanyaan_count|label:Pertanyaan|sortable:false|className:text-center|width:120px'),
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
                'kompetensi' => ['required', 'unique:ref_wawancara_kompetensi,kompetensi'],
                // 'uraian' => ['required']
            ]
        );
        return Kompetensi::createByRequest($request);
    }


    public function show(Kompetensi $kompetensi)
    {
        return $this->render($this->view . '.show', ['record' => $kompetensi]);
    }


    public function edit(Kompetensi $kompetensi)
    {
        return $this->render($this->view . '.edit', ['record' => $kompetensi]);
    }


    public function update(Request $request, Kompetensi $kompetensi)
    {
        $request->validate(
            [
                'kompetensi' => ['required', 'unique:ref_wawancara_kompetensi,kompetensi,' . $kompetensi->id],
                // 'uraian' => ['required']
            ]
        );
        return $kompetensi->updateByRequest($request);
    }


    public function destroy(Kompetensi $kompetensi)
    {
        return $kompetensi->deleteByRequest();
    }
}
