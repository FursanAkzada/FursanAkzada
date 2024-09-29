<?php

namespace Modules\Settings\Http\Controllers\Profiles;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Settings\Entities\AuditTrail;
use Yajra\DataTables\DataTables;

class ActivityController extends Controller
{

    protected $module = 'settings';
    protected $route  = 'settings.activity';
    protected $view   = 'settings::activity';

    public function __construct()
    {
        $this->setPrepare([
            'module' => $this->module,
            'route'  => $this->route,
            'view'  => $this->view,
            'title'  => 'Aktivitas Log',
            'breadcrumb' => [
                'Aktivitas Log' => '/',
            ]
        ]);
    }

    public function grid()
    {
        $records = AuditTrail::me()->when($keyword = request()->module_name, function ($q) use ($keyword) {
            $q->whereLike(['module'], $keyword);
        });
        return DataTables::of($records)
            ->order(function ($q) {
                $q->orderBy('created_at', 'desc');
            })
            ->editColumn('num', function ($r) {
                return request()->start;
            })
            ->editColumn('name', function ($r) {
                return $r->user->name;
            })
            ->editColumn('module', function ($r) {
                return \Base::getModules($r->module);
            })
            ->editColumn('access', function ($r) {
                return '<span class="mr-2">' . $r->ip_address . '</span> ' . $r->actionRaw();
            })
            ->editColumn('browser', function ($r) {
                return '<div class="font-size-xs">
            <span>' . $r->browser . '</span><br>
            <span><i class="far fa-clock mr-1"></i> ' . $r->created_at->diffForHumans() . '</span>
            </div>';
            })
            ->editColumn('action', function ($r) {
                return $this->makeDropdown([
                    [
                        'type'  => 'show',
                        'id'    => $r->id,
                        'label' => 'Detail',
                        'attrs' => 'data-modal-size="modal-lg"'
                    ]
                ]);
            })
            ->rawColumns(['action', 'module', 'action', 'browser', 'access'])
            ->make(true);
    }

    public function index()
    {
        $this->setPrepare([
            'tableStruct' => [
                'datatable_1' => [
                    $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                    // $this->makeColumn('name:name|label:User|sortable:false'),
                    $this->makeColumn('name:module|label:Menu|sortable:false'),
                    $this->makeColumn('name:access|label:Action|sortable:false'),
                    // $this->makeColumn('name:ip_address|label:Ip Address|sortable:false'),
                    $this->makeColumn('name:browser|label:Browser|sortable:false'),
                    $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px'),
                ],
            ],
        ]);
        return $this->render($this->view . '.index');
    }

    public function show(AuditTrail $activity)
    {
        return $this->render($this->view . '.show', [
            'record' => $activity,
        ]);
    }
}
