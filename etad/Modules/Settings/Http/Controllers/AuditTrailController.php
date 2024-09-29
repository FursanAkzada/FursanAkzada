<?php

namespace Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Settings\Entities\AuditTrail;
use Modules\Settings\Entities\Menu;
use Yajra\DataTables\DataTables;


class AuditTrailController extends Controller
{
    protected $module = 'settings.audit-trail';
    protected $route  = 'settings.audit-trail';
    protected $view   = 'settings::audit-trail';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view'   => $this->view,
                'title'  => 'Audit Trail',
                'breadcrumb' => [
                    'Konfigurasi'    => '/',
                    'Audit Trail' => '/',
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $records = AuditTrail::when(
            $modul =  request()->post('module_name'),
            function ($q) use ($modul) {
                $q->where('module', 'LIKE', '%' . $modul . '%');
            }
        );
        return DataTables::of($records)
            ->order(
                function ($query) {
                    $query->when(
                        !request()->has('order'),
                        function ($q) {
                            $q->orderBy('created_at', 'desc');
                        }
                    );
                }
            )
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'name',
                function ($r) {
                    return $r->user->name;
                }
            )
            ->editColumn(
                'module',
                function ($r) {
                    return \Base::getModules($r->module);
                }
            )
            ->editColumn(
                'access',
                function ($r) {
                    return $r->actionRaw();
                }
            )
            ->editColumn(
                'ip_address',
                function ($r) {
                    return $r->ip_address;
                }
            )
            ->editColumn(
                'browser',
                function ($r) {
                    return
                        $r->createdByRawAuditTrail();
                }
            )
            ->editColumn(
                'action',
                function ($r) {
                    return $this->makeDropdown(
                        [
                            [
                                'type'  => 'show',
                                'id'    => $r->id,
                                'label' => 'Detail'
                            ]
                        ]
                    );
                }
            )
            ->rawColumns(
                [
                    'name',
                    'ip_address',
                    'action', 'module', 'action', 'browser', 'access'
                ]
            )
            ->make(true);
    }

    public function index()
    {
        $AUDIT_TRAIL__MODULES = AuditTrail::select('module')
            ->distinct('module')
            ->get();
        $this->setPrepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:name|label:Nama|sortable:false'),
                        $this->makeColumn('name:module|label:Menu|sortable:false'),
                        $this->makeColumn('name:access|label:Aksi|sortable:false'),
                        $this->makeColumn('name:ip_address|label:IP Address|sortable:false'),
                        $this->makeColumn('name:browser|label:Diperbarui|sortable:false|className:text-center|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px'),
                    ],
                ],
            ]
        );
        $records = Menu::orderBy('order')->get();
        return $this->render(
            $this->view . '.index',
            compact('AUDIT_TRAIL__MODULES', 'records')
        );
    }

    public function create()
    {
        return view('settings::create');
    }

    public function store(Request $request)
    {
    }

    public function show(AuditTrail $audit_trail)
    {
        return $this->render(
            $this->view . '.show',
            [
                'record' => $audit_trail,
            ]
        );
    }

    public function edit($id)
    {
        return view('settings::edit');
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
