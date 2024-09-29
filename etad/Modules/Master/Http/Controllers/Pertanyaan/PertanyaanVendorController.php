<?php

namespace Modules\Master\Http\Controllers\Pertanyaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Master\Entities\Pertanyaan\Vendor;
use Yajra\DataTables\DataTables;

class PertanyaanVendorController extends Controller
{
    protected $module = 'master.pertanyaan.vendor';
    protected $route  = 'master.pertanyaan.vendor';
    protected $view   = 'master::pertanyaan.vendor';
    protected $perms = 'master';

    public function __construct()

    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'perms'  => $this->perms,
                'title'  => 'Pertanyaan Vendor',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Pertanyaan' => route($this->route . '.index'),
                    'Pertanyaan Vendor' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $records = Vendor::when(
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
                'action',
                function ($r) {
                    $buttons = $this->makeDropdown(
                        [
                            'type:show|class:base-modal--render|attrs:data-modal-size="modal-lg"|page:true|label:Lihat|id:' . $r->id,
                            'type:edit|class:base-modal--render|attrs:data-modal-size="modal-lg"|page:true|label:Ubah|id:' . $r->id,
                            'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus Pertanyaan?"|hidden:' . !auth()->user()->checkPerms($this->perms . '.delete'),
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
                        $this->makeColumn('name:pertanyaan|label:Nama|sortable:false|className:text-left'),
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
                'pertanyaan' => ['required', 'unique:ref_penilaian_vendor,pertanyaan']
            ]
        );
        return Vendor::createByRequest($request);
    }


    public function show($id)
    {
        $record = Vendor::find($id);
        return $this->render($this->view . '.show', ['record' => $record]);
    }


    public function edit($id)
    {
        $record = Vendor::find($id);
        return $this->render($this->view . '.edit', ['record' => $record]);
    }


    public function update(Request $request, $id)
    {
        $record = Vendor::find($id);
        return $record->updateByRequest($request);
    }


    public function destroy($id)
    {
        $record = Vendor::find($id);
        return $record->deleteByRequest();
    }
}
