<?php

namespace Modules\Punishment\Http\Controllers;

use App\Entities\EHC\Jabatan;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Master\Entities\Tad\Tad;
use Modules\Punishment\Entities\Punishment\Punishment;
use Yajra\DataTables\DataTables;

class PunishmentController extends Controller
{
    protected $module = 'punishment';
    protected $route  = 'punishment.form';
    protected $perms  = 'pu.punishment';
    protected $view   = 'punishment::punishment.form';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'perms'  => $this->perms,
                'route'  => $this->route,
                'view'  => $this->view,
                'title'  => 'Punishment',
                'breadcrumb' => [
                    'Reward & Pembinaan' => '/',
                    'Punishment' => '/',
                ]
            ]
        );
    }

    public function grid(Request $request)
    {
        $user = auth()->user();
        $records = Punishment::with('tad')
            ->when(
                $keyword = request()->keyword,
                function () use ($keyword) {
                }
            )
            ->orderBy('created_at', 'DESC');
        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'sk',
                function ($r) {
                    return '<div>
                    <span><span class="text-bold">SK : </span>' . $r->sk . '</span><br>
                    <span><span class="text-bold">Tanggal SK : </span>' . $r->tanggal_sk->format('d/m/Y') . '</span>
                </div>';
                }
            )
            ->editColumn(
                'jenis',
                function ($r) {
                    return $r->jenisPunishment->Lengkap;
                }
            )
            ->editColumn(
                'created_at',
                function ($r) {
                    return $r->createdByRaw2();
                }
            )
            ->editColumn(
                'status',
                function ($record) {
                    if ($record->status == 'submit') {
                        return '<span class="badge badge-primary text-white">Waiting Approval</span></span>';
                    } elseif ($record->status == 'approved') {
                        return '<span class="badge badge-success text-white">Completed</span></span>';
                    } elseif ($record->status == 'rejected') {
                        return '<span class="badge badge-danger text-white">Ditolak</span></span>';
                    } elseif ($record->status == 'draft') {
                        return '<span class="badge badge-warning text-white">Draft</span></span>';
                    }
                }
            )
            ->editColumn(
                'action',
                function ($r) use ($user) {
                    $buttons = [];
                    $buttons[] = 'type:show|attrs:data-modal-size="modal-xl"|label:Detail|page:true|id:' . $r->id;
                    if ($user->checkPerms($this->perms . '.edit') && in_array($r->status, [0, 'draft', 'rejected'])) {
                        $buttons[] = 'type:edit|page:true|id:' . $r->id;
                    }
                    if ($user->checkPerms($this->perms . '.approve') && in_array($r->status, ['1', 'submit']) && $r->checkApproval()) {
                        $buttons[] = [
                            'type' => 'show',
                            'page' => true,
                            'id' => $r->id,
                            'label' => 'Approval',
                            'icon' => 'fa fa-check text-primary',
                            'url' => route($this->route . '.show', $r->id),
                        ];
                    }
                    if ($user->checkPerms($this->perms . '.delete') && in_array($r->status, [0, 'draft', 'rejected'])) {
                        $buttons[] = 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Yakin Menghapus Data Punishment TAD ?"';
                    }
                    $buttons[] = [
                        'type' => 'show',
                        'label' => 'Riwayat',
                        'icon' => 'fa flaticon2-time text-success',
                        'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                        'url' => route($this->route . '.riwayat', $r->id),
                        'id' => $r->id,
                    ];
                    return $this->makeDropdown($buttons);
                }
            )
            ->rawColumns(['sk', 'status', 'created_at', 'action'])
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
                        $this->makeColumn('name:tad.nama|label:Personil|sortable:false|className:text-left'),
                        $this->makeColumn('name:sk|label:SK & Tanggal|sortable:false|className:text-left'),
                        $this->makeColumn('name:eviden|label:Eviden|sortable:false|className:text-left'),
                        $this->makeColumn('name:jenis|label:Jenis Punishment|sortable:false|className:text-left'),
                        $this->makeColumn('name:status|label:Status|sortable:false|className:text-center'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|className:text-left|width:120px'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );
        return $this->render($this->view . '.index');
    }

    public function create()
    {
        $user = auth()->user();
        $so_id = $user->position->org_struct_id ?? null;
        $POSISI_TAD = Jabatan::with(
            [
                'personils' => function ($q) use ($user) {
                    $q
                        ->select('id', 'jabatan_id', 'nama')
                        ->with(
                            [
                                'kepegawaian' => function ($q) {
                                }
                            ]
                        )
                        ->whereHas(
                            'kepegawaian',
                            function ($q) {
                                $q
                                    ->whereNull('out_at')
                                    ->orWhereNull('resign_at');
                            }
                        )
                        ->when(
                            $user->isVendor,
                            function ($q) use ($user) {
                                $q->where('vendor_id', $user->vendor_id);
                            }
                        )
                        ->orderBy('nama', 'ASC');
                }
            ]
        )
            ->whereHas(
                'personils',
                function ($q) use ($user) {
                    $q
                        ->when(
                            $user->isVendor,
                            function ($q) use ($user) {
                                $q->where('vendor_id', $user->vendor_id);
                            }
                        )
                        ->whereHas(
                            'kepegawaian',
                            function ($q) {
                                $q
                                    ->whereNull('out_at')
                                    ->orWhereNull('resign_at');
                            }
                        );
                }
            )
            ->orderBy('NM_UNIT', 'ASC')
            ->get();
        return $this->render(
            $this->view . '.create',
            compact('POSISI_TAD')
        );
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'tad_id'          => ['required'],
                'sk'              => ['required', 'unique:trans_punishment,sk'],
                'tanggal_sk'      => ['required', 'date_format:d/m/Y'],
                'eviden'          => ['required'],
                'jenis_id'        => ['required', 'exists:ref_ehc_jenis_reward,sandi'],
                'tanggal_mulai'   => ['required', 'date_format:d/m/Y'],
                'tanggal_selesai' => ['required', 'date_format:d/m/Y'],
            ]
        );
        return Punishment::createByRequest($request);
    }

    public function show($id)
    {
        $record = Punishment::findOrFail($id);
        return $this->render(
            $this->view . '.show',
            compact('record')
        );
    }

    public function edit($id)
    {
        $record = Punishment::findOrFail($id);
        $user = auth()->user();
        $so_id = $user->position->org_struct_id ?? null;
        $POSISI_TAD = Jabatan::with(
            [
                'personils' => function ($q) use ($user) {
                    $q
                        ->select('id', 'jabatan_id', 'nama')
                        ->with(
                            [
                                'kepegawaian' => function ($q) {
                                }
                            ]
                        )
                        ->whereHas(
                            'kepegawaian',
                            function ($q) {
                                $q
                                    ->whereNull('out_at')
                                    ->orWhereNull('resign_at');
                            }
                        )
                        ->when(
                            $user->isVendor,
                            function ($q) use ($user) {
                                $q->where('vendor_id', $user->vendor_id);
                            }
                        )
                        ->orderBy('nama', 'ASC');
                }
            ]
        )
            ->whereHas(
                'personils',
                function ($q) use ($user) {
                    $q
                        ->when(
                            $user->isVendor,
                            function ($q) use ($user) {
                                $q->where('vendor_id', $user->vendor_id);
                            }
                        )
                        ->whereHas(
                            'kepegawaian',
                            function ($q) {
                                $q
                                    ->whereNull('out_at')
                                    ->orWhereNull('resign_at');
                            }
                        );
                }
            )
            ->orderBy('NM_UNIT', 'ASC')
            ->get();
        return $this->render(
            $this->view . '.edit',
            compact('POSISI_TAD', 'record')
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'tad_id'          => ['required'],
                'sk'              => ['required', 'unique:trans_punishment,sk,'.$id],
                'tanggal_sk'      => ['required', 'date_format:d/m/Y'],
                'eviden'          => ['required'],
                'jenis_id'        => ['required', 'exists:ref_ehc_jenis_reward,sandi'],
                'tanggal_mulai'   => ['required', 'date_format:d/m/Y'],
                'tanggal_selesai' => ['required', 'date_format:d/m/Y'],
            ]
        );
        return Punishment::findOrFail($id)->updateByRequest($request);
    }

    public function destroy($id)
    {
        return Punishment::find($id)->deleteByRequest();
    }

    public function riwayat($id)
    {
        $record = Punishment::with(
            [
                'logs' => function ($q) {
                    $q->orderBy('created_at', 'DESC');
                }
            ]
        )->find($id);
        $this->setPrepare(
            [
                'title'  => 'Punishment TAD | <span class="label label-xl label-danger label-inline text-nowrap">' . ($record->tad->nama) . ' | SK ' . $record->sk . '</span>',
            ]
        );
        $show_title = true;
        return $this->render(
            $this->view . '.riwayat',
            compact('record', 'show_title')
        );
    }
    public function approvalSave(Request $request, $id)
    {
        if ($request->is_submit != 'approved') {
            $request->validate(
                [
                    'keterangan' => 'required|string|max:65000'
                ],
                [],
                [
                    'keterangan' => 'Keterangan',
                ]
            );
        }
        $record = Punishment::findOrFail($id);
        return $record->handleApproveSave($request);
    }
}
