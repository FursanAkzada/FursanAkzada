<?php

namespace Modules\Master\Http\Controllers\Geografis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KelDesaController extends Controller
{
    protected $module = 'master';
    protected $route  = 'master.geografis.kel-desa';
    protected $view   = 'master::geografis.kel-desa';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'title'  => 'Kelurahan/Desa',
                'breadcrumb' => [
                    'Parameter' => '/',
                    'Geografis' => '/',
                    'Kelurahan/Desa' => '/',
                ]
            ]
        );
    }

    public function grid()
    {
        $records = [];
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'unit',
                function ($r) {
                    return $r->unit ? $r->unit->name : '-';
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
                    $buttons = '';
                    $buttons .= $this->makeDropdown(
                        [
                            'type:edit|id:' . $r->id,
                            'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r->name . '?"',
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
                // 'perms'
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:name|label:Provinsi|sortable:false|className:text-left'),
                        $this->makeColumn('name:name|label:Kabupaten/Kota|sortable:false|className:text-left'),
                        $this->makeColumn('name:name|label:Kecamatan|sortable:false|className:text-left'),
                        $this->makeColumn('name:name|label:Kelurahan/Desa|sortable:false|className:text-left'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false'),
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
}
