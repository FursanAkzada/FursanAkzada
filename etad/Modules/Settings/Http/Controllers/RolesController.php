<?php

namespace Modules\Settings\Http\Controllers;

use App\Entities\Group;
use App\Entities\User;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RolesController extends Controller
{

    protected $module = 'settings.roles';
    protected $route  = 'settings.roles';
    protected $view   = 'settings::roles';
    protected $perms = 'setting';
    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view'  => $this->view,
                'perms'  => $this->perms,
                'title'  => 'Hak Akses',
                'breadcrumb' => [
                    'Konfigurasi' => '/',
                    'Hak Akses' => '/',
                ]
            ]
        );
    }

    public function grid()
    {
        $records = Group::when(
            $keyword = request()->keyword,
            function ($q) use ($keyword) {
                $q->whereLike(['name'], $keyword);
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
                'name',
                function ($r) {
                    return $r->name;
                }
            )
            ->editColumn(
                'users',
                function ($r) {
                    return $r->users->count() . ' Users';
                }
            )
            ->editColumn(
                'perms',
                function ($r) {
                    return $r->permissions->count() . ' Permissions';
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
                        'type:show|page:true|icon:far fa-check-circle text-success|label:Assign Permissions|id:' . $r->id,
                        'type:edit|id:' . $r->id,
                    ];
                    if ($r->users->count() == 0 && !in_array($r->id, [6])) {
                        $action[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->name . '?"|hidden:' . !auth()->user()->checkPerms($this->perms . '.delete');
                    }
                    return $this->makeDropdown($action);
                }
            )
            ->rawColumns(
                [
                    'name',
                    'users',
                    'perms',
                    'action', 'created_at'
                ]
            )
            ->make(true);
    }

    public function index()
    {
        $this->setPrepare(
            [
                // 'perms' => $this->perms.'.index',
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:name|label:Nama|sortable:false'),
                        $this->makeColumn('name:users|label:Jumlah user|sortable:false'),
                        $this->makeColumn('name:perms|label:Hak Akses|sortable:false'),
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
        return $this->render(
            $this->view . '.create',
            [
                'users' => User::orderBy('name', 'asc')->get()
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|unique:sys_groups,name',
                // 'users' => 'required|exists:sys_users,id'
            ]
        );
        return Group::createByRequest($request);
    }

    public function show($id)
    {
        $record = Group::find($id);
        $this->setPrepare(
            [
                'title'  => 'Assign Permissions | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->name . '</span>',
                'breadcrumb' => [
                    'Settings' => '/',
                    'Roles Management' => '/',
                    'Assign Permissions' => '/',
                ]
            ]
        );
        return $this->render(
            $this->view . '.show',
            [
                'record' => $record
            ]
        );
    }

    public function edit($id)
    {
        $record = Group::find($id);
        return $this->render(
            $this->view . '.edit',
            [
                'record'    => $record,
                'users'     => User::orderBy('name', 'asc')->get()
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $record = Group::find($id);
        $request->validate(
            [
                'name' => 'required|unique:sys_groups,name,' . $id,
                // 'users' => 'required|exists:sys_users,id'
            ]
        );
        return $record->updateByRequest($request);
    }

    public function destroy($id)
    {
        $record = Group::find($id);
        return $record->deleteByRequest();
    }

    public function grant(Request $request, $id)
    {
        $record = Group::find($id);
        return $record->grantByRequest($request);
    }
    public function selectRole($search, Request $request)
    {
        // $items = Group::keywordBy('name')->orderBy('name');
        $items = Group::orderBy('name');
        // return [188, $search, $items->get()];
        switch ($search) {
            case 'all':
                $items = $items;
                break;
            case 'approver':
                // $perms = str_replace('_', '.', $request->perms) . '.approve';
                $perms = $request->perms . '.approve';
                $items = $items->whereHas(
                    'permissions',
                    function ($q) use ($perms) {
                        $q->where('name', $perms);
                    }
                );
                break;

            default:
                $items = $items->whereNull('id');
                break;
        }
        $items = $items->paginate();
        return $this->responseSelect2($items, 'name', 'id');
    }
}
