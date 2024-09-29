<?php

namespace Modules\Settings\Http\Controllers;

use App\Entities\Group;
use App\Entities\Role;
use App\Entities\User;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Settings\Entities\Menu;
use Yajra\DataTables\Facades\DataTables;

class FlowController extends Controller
{

    protected $module = 'settings.flow';
    protected $route  = 'settings.flow';
    protected $perms  = 'setting';
    protected $view   = 'settings::flow';

    public function __construct()
    {
        $this->setPrepare(
            [
                'perms' => $this->perms,
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Flow Approval',
                'breadcrumb' => [
                    'Konfigurasi' => '/',
                    'Flow Approval' => '/',
                ]
            ]
        );
    }

    public function grid()
    {
        $records = Menu::orderBy('order')
            ->when(
                $module = request()->module_name,
                function ($q) use ($module) {
                    $q->where('module', 'LIKE', $module . '%');
                }
            );

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
                    if (isset($r->parent->parent)) {
                        return '<span class="pl-8">&#8627;</span> ' . $r->name;
                    } elseif ($r->parent) {
                        return '<span class="pl-4">&#8627;</span> ' . $r->name;
                    } else {
                        return "<b>" . $r->name . "</b>";
                    }
                }
            )
            ->editColumn(
                'flows',
                function ($r) {
                    if ($r->child->count() != 0) {
                        return "";
                    }

                    $html = "<div class=\"d-flex align-items-center justify-content-center\">";
                    $colors = [
                        1 => 'primary',
                        2 => 'info',
                    ];
                    $orders = $r->flows()
                        ->get()
                        ->groupBy('order');
                    foreach ($orders as $i => $flows) {
                        foreach ($flows as $j => $flow) {
                            $html .= "<span class=\"label label-light-{$colors[$flow->type]} font-weight-bold label-inline\" data-toggle=\"tooltip\" title=\"{$flow->show_type}\">{$flow->group->name}</span>";
                            if (!($i === $orders->keys()->last() && $j === $flows->keys()->last())) {
                                $html .= "<i class=\"mx-2 fas fa-angle-double-right text-muted\"></i>";
                            }
                        }
                    }
                    $html .= "</div>";

                    return $html;
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
                    $actions = [];
                    if ($r->child->count() == 0) {
                        $actions[] = [
                            'type' => 'edit',
                            'page' => true,
                            'icon' => 'far fa-check-circle text-success',
                            'label' => 'Assign Approval',
                            'id' => $r->id,
                        ];
                        $actions[] = [
                            'type' => 'show',
                            'label' => 'Riwayat',
                            'icon' => 'fa flaticon2-time text-dark',
                            'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                            'url' => route($this->route . '.riwayat', $r->id),
                            'id' => $r->id,
                        ];
                    }
                    return $this->makeDropdown($actions);
                }
            )
            ->rawColumns(
                [
                    'created_at',
                    'name',
                    'action', 'flows'
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
                        $this->makeColumn('name:name|label:Menu|sortable:false|className:text-left|width:220px'),
                        $this->makeColumn('name:flows|label:Alur Persetujuan|sortable:false|className:text-left'),
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
        return abort(404);
    }

    public function store(Request $request)
    {
        return abort(404);
    }

    public function show(Group $group)
    {
        return abort(404);
    }

    public function edit($id)
    {
        $record = Menu::findOrFail($id);
        $groups = Group::orderBy('name')->get();
        // $groups = Role::orderBy('name')->get();
        return $this->render($this->view . '.edit', compact('record', 'groups'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'flows.*.group_id' => 'required|distinct|exists:sys_groups,id',
                'flows.*.type'     => 'required'
            ],
            [],
            [
                'flows.*.group_id' => 'Group',
                'flows.*.type'     => 'Tipe',
            ]
        );

        $record = Menu::findOrFail($id);

        return $record->handleStoreOrUpdate($request);
    }

    public function destroy($id)
    {
        return abort(404);
    }

    public function riwayat($id)
    {
        $record = Menu::where('id', $id)->first();
        $this->setPrepare(
            [
                'title'  => 'Flow Approval | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->name . '</span>',
            ]
        );
        $show_title = true;
        return $this->render(
            $this->view . '.riwayat',
            compact('record', 'show_title')
        );
    }
}
