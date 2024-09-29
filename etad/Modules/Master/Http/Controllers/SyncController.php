<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Master\Entities\Tad\Tad;
use App\Entities\EHC\Tad as TadEhc;
use Yajra\DataTables\DataTables;

class SyncController extends Controller
{
    protected $module = 'master';
    protected $route  = 'master.sync';
    protected $view   = 'master::sync';
    protected $perms = 'tad';

    public function __construct()
    {
        $this->setPrepare([
            'module'     => $this->module,
            'route'      => $this->route,
            'view'       => $this->view,
            // 'perms' => $this->perms,
            'title'      => 'TAD Sync',
            'breadcrumb' => [
                'TAD Sync' => '/',
            ]
        ]);
    }

    public function grid()
    {
        $records = Tad::filters();

        return DataTables::of($records)
            // ->order(function ($query) {
            //     $query->when(!request()->has('order'), function ($q) {
            //         $q->orderBy('nama', 'asc');
            //     });
            // })
            ->editColumn('num', function ($r) {
                return request()->start;
            })
            ->editColumn('nama', function ($r) {
                return $this->render($this->view . '.partials.grid.nama', ['r' => $r]);
            })
            ->editColumn('status_pekerja', function ($r) {
                return $this->render($this->view . '.partials.grid.status-pekerja', ['r' => $r]);
            })
            ->editColumn('created_at', function ($r) {
                return $r->createdByRaw2();
            })
            ->editColumn('vendor', function ($r) {
                return $r->vendor ? $r->vendor->nama : '-';
            })
            ->editColumn('status', function ($r) {
                if ($r->same_as_ehc) {
                    return '<span class="badge badge-primary">Sudah Sesuai</span>';
                }
                return '<span class="badge badge-danger">Belum Sesuai</span>';
            })
            ->editColumn('action', function ($r) {
                $buttons = $this->makeDropdown([
                    'type:custom|icon:fas fa-arrow-right text-success|class:base-modal--confirm|label:Sync Ke EHC|url:sync/to/' . $r->id,
                ]);
                return $buttons;
            })
            ->rawColumns(['action', 'created_at', 'nama', 'status'])
            ->make(true);
    }

    public function index()
    {
        $column = [
            'datatable_1' => [
                $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                $this->makeColumn('name:nama|label:Nama|sortable:false|className:text-left'),
                $this->makeColumn('name:status_pekerja|label:Status Pekerja|sortable:false|className:text-left'),
                $this->makeColumn('name:vendor|label:Cabang/Vendor|sortable:false|className:text-left'),
                $this->makeColumn('name:status|label:Status|sortable:false|className:text-center'),
                // $this->makeColumn('name:created_at|label:Created At|sortable:false'),
                $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
            ]
        ];

        /* For Vendor */
        if (auth()->user()->user_type == 'web') {
            $column = [
                'datatable_1' => [
                    $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                    // $this->makeColumn('name:nik|label:NIK|sortable:false|className:text-left'),
                    $this->makeColumn('name:nama|label:Nama|sortable:false|className:text-left'),
                    $this->makeColumn('name:jabatan|label:Jabatan|sortable:false|className:text-left'),
                    $this->makeColumn('name:status_pekerja|label:Status Pekerja|sortable:false|className:text-left'),
                    $this->makeColumn('name:created_at|label:Diperbarui|sortable:false'),
                    $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                ]
            ];
        }

        $this->setPrepare([
            // 'perms' => $this->perms.'.index',
            'tableStruct' => $column,
        ]);
        return $this->render($this->view . '.index');
    }

    public function to(Tad $tad)
    {
        return $tad->syncToByRequest(); // Send To EHC
    }

    public function toMass()
    {
        return Tad::syncToMassByRequest(); // Send multiple To EHC
    }
}
