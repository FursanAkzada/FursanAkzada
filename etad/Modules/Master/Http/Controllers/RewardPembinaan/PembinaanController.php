<?php

namespace Modules\Master\Http\Controllers\RewardPembinaan;

use App\Entities\EHC\JenisPunishment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PembinaanController extends Controller
{
    protected $module = 'master.rp.pembinaan';
    protected $route  = 'master.rp.pembinaan';
    protected $view   = 'master::rp.pembinaan';
    protected $perms   = 'master';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'title'  => 'Jenis Pembinaan',
                'breadcrumb' => [
                    'Parameter' => route($this->route . '.index'),
                    'Reward & Pembinaan' => route($this->route . '.index'),
                    'Jenis Pembinaan' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $records = JenisPunishment::pembinaan()
            ->when(
                $keyword = request()->keyword,
                function ($q) use ($keyword) {
                    $q->whereLike(['Lengkap'], $keyword);
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
                'Lengkap',
                function ($r) {
                    return $r->Lengkap;
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
                        'type:show|class:base-modal--render|page:true|label:Lihat|id:' . $r->sandi,
                        'type:edit|class:base-modal--render|page:true|label:Ubah|id:' . $r->sandi,
                    ];
                    $have_delete_perm = auth()->user()->checkPerms($this->perms . '.delete');
                    $can_delete = $r->canDelete() && $have_delete_perm;
                    // return [$r->canDelete(), $have_delete_perm];
                    if ($can_delete) {
                        $action[] = 'type:delete|id:' . $r->sandi . '|attrs:data-confirm-text="Hapus ' . $r->Lengkap . '?"';
                    }
                    return $this->makeDropdown($action);
                }
            )
            ->rawColumns(
                [
                    'Lengkap',
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
                        $this->makeColumn('name:Lengkap|label:Nama|sortable:false|className:text-center'),
                        $this->makeColumn('name:description|label:Deskripsi|sortable:false|className:text-center|width:400px'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
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
                'Lengkap' => ['required', 'unique:ref_ehc_jenis_reward,Lengkap']
            ]
        );
        return JenisPunishment::createByRequest($request);
    }

    public function show(JenisPunishment $pembinaan)
    {
        return $this->render($this->view . '.show', ['record' => $pembinaan]);
    }

    public function edit($id)
    {
        $record = JenisPunishment::find($id);
        return $this->render($this->view . '.edit', ['record' => $record]);
    }

    public function update(Request $request, $id)
    {
        $record = JenisPunishment::find($id);
        return $record->updateByRequest($request);
    }

    public function destroy($id)
    {
        $record = JenisPunishment::find($id);
        // return $record;
        return $record->deleteByRequest();
    }
}
