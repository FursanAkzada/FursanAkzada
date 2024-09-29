<?php

namespace Modules\Pengajuan\Http\Controllers\Tad;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Master\Entities\KategoriVendor;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Entities\Tad\Penerimaan;
use Modules\Pengajuan\Entities\Tad\SummaryWawancara;
use Modules\Pengajuan\Http\Requests\CcRequest;
use Modules\Pengajuan\Http\Requests\PenerimaanRequest;
use Yajra\DataTables\DataTables;

class PengajuanPenerimaanController extends Controller
{
    protected $module   = 'pengajuan.tad.penerimaan';
    protected $route    = 'pengajuan.penerimaan';
    protected $view     = 'pengajuan::tad.penerimaan';
    protected $perms    = 'pengajuan.tad.penerimaan';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view' => $this->view,
                'perms' => $this->perms,
                'title'  => 'Penerimaan',
                'breadcrumb' => [
                    'Pengajuan'     => url('pengajuan/tad/penerimaan'),
                    'Penerimaan' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user       = auth()->user();
        $user_id    = $user->id;
        $so_id      = $user->position->org_struct_id ?? null;
        $records = SummaryWawancara::with('kandidat')->where('is_fallback', false)
            ->whereHas('kandidat', function ($q) use ($so_id, $user, $user_id) {
                $q->whereHas('summary', function ($qq) use ($so_id, $user, $user_id) {
                    $qq->whereHas('requirement', function ($qqq) use ($so_id, $user, $user_id) {
                        $qqq->when(
                            $user->isVendor,
                            function ($qq) use ($user) {
                                $qq->whereIn('status', ['approved', 'vendor.submit', 'hc.verified', 'completed'])->where('vendor_id', $user->vendor_id);
                            }
                        )
                            ->whereHas(
                                'pengajuan',
                                function ($qqqq) use ($so_id, $user, $user_id) {
                                    $qqqq
                                        ->when(
                                            $user->isEhc && !$user->cekDivisiHC() && ($so_id),
                                            function ($qqq) use ($so_id, $user) {
                                                $qqq->where('so_id', $so_id);
						    // ->orWhereHas(
                                                    // 'approvalsAll',
                                                    // function ($q) use ($user) {
                                                    //    $q->where(
                                                    //        function ($qq) use ($user) {
                                                    //            $qq->where('user_id', auth()->user()->id)
                                                    //                ->whereIn('group_id', auth()->user()->getGroupIds());
                                                    //        }
                                                    //    );
                                                   // }
                                                // );
                                            }
                                        )
					->when(
               				    $personil = request()->personil,
                			    function ($q) use ($personil) {
                    			        $q->where('personil', 'LIKE', '%' . $personil . '%');
                			    }
            				)
                                        ->when(
                                            $keyword = request()->keyword,
                                            function ($q) use ($keyword) {
                                                $q->whereLike(['no_tiket', 'created_at'], $keyword);
                                            }
                                        )
					->when($vendor_id = request()->vendor_id, function ($q) use ($vendor_id) {
                    	    		    $q->where('vendor_id', $vendor_id);
                			})
                                        ->when(
                                            $location_id = request()->location_id,
                                            function ($q) use ($location_id) {
                                                $q->where('so_id', $location_id);
                                            }
                                        )
                                        ->when(
                                            $date_start = request()->date_start,
                                            function ($qq) use ($date_start) {
                                                $qq->where('tgl_pengajuan', '>=', Carbon::createFromFormat('d/m/Y', $date_start)->format('Y-m-d'));
                                            }
                                        )
                                        ->when(
                                            $date_end = request()->date_end,
                                            function ($qq) use ($date_end) {
                                                $qq->where('tgl_pengajuan', '<=', Carbon::createFromFormat('d/m/Y', $date_end)->format('Y-m-d'));
                                            }
                                        );
                                }
                            );
                    });
                });
            })
            ->where('status', 'completed')
            ->orderBy('updated_at', 'DESC');

        return DataTables::of($records)
            ->editColumn(
                'num',
                function ($r) {
                    return request()->start;
                }
            )
            ->editColumn(
                'nama_kandidat',
                function ($r) {
                    return $r->kandidat->tad->nama;
                }
            )
            ->editColumn(
                'skor',
                function ($r) {
                    return $r->details->sum('value');
                }
            )
            ->editColumn(
                'pengajuan',
                function ($r) {
                    return $r->kandidat->summary->pengajuan->no_tiket . "<br>" . $r->kandidat->summary->pengajuan->tgl_pengajuan->format('d/m/Y');
                }
            )
            ->editColumn(
                'vendor',
                function ($r) {
                    return $r->kandidat->summary->requirement->vendor->nama . "<br>" . $r->kandidat->summary->requirement->jabatan->NM_UNIT;
                }
            )
            ->editColumn(
                'tanggal_pengajuan',
                function ($r) {
                    return '<div>'.
                            ($r->kandidat->summary->pengajuan->so->name ?? '') . '<br>Tahun ' . $r->kandidat->summary->pengajuan->year . ' Semester ' . $r->kandidat->summary->pengajuan->semester.
                        '</div>';
                }
            )
            ->editColumn(
                'keputusan',
                function ($r) use ($user) {
                    $temp = Penerimaan::where('wawancara_id', $r->id)->first();
                    $str = '';

                    if (isset($temp->keputusan)) {
                        if (isset($temp->tgl_keputusan)) {
                            $str .= $temp->tgl_keputusan->format('d/m/Y') . "<br>";
                        }
                        if ($temp->keputusan == "Ditolak") {
                            $str .= 'Ditolak' . '';
                        } else {
                            $str .= 'Diterima' . '';
                        }
                    }
                    return $str;
                }
            )
            ->editColumn(
                'status',
                function ($r) use ($user) {
                    $temp = Penerimaan::where('wawancara_id', $r->id)->first();
                    if ($temp != null) {
                        return $temp->labelStatus($temp->status ?? 'new');
                    } else {
                        return \Base::getStatus('new');
                    }
                }
            )
            ->editColumn(
                'created_at',
                function ($r) {
                    $temp = Penerimaan::where('wawancara_id', $r->id)->first();
                    if ($temp != null) {
                        if ($temp->status != 'new') {
                            return $temp->createdByRaw2();
                        }
                    }
                    return '';
                }
            )
            ->editColumn(
                'action',
                function ($r) use ($so_id) {
                    $actions = [];
                    $user = auth()->user();
                    $pengajuan_penerimaan = Penerimaan::firstOrCreate([
                        'wawancara_id' => $r->id,
                    ]);
                    if ($pengajuan_penerimaan) {
                        if (in_array($pengajuan_penerimaan->status, ['draft', 'waiting.approval', 'completed', 'submit'])) {
                            // $actions[] = 'type:show|label:Detail|page:true|id:' . $pengajuan_penerimaan->id;
                            if ($user->checkPerms($this->perms . '.view')) {
                                $actions[] = [
                                    'type' => 'show',
                                    'page' => true,
                                    'id' => $r->id,
                                    'label' => 'Detail',
                                    'url' => route($this->route . '.show', $pengajuan_penerimaan->id),
                                ];
                            }
                            if ($pengajuan_penerimaan->checkAction('edit', $this->perms)) {
                                $actions[] = 'type:edit|page:true|id:' . $pengajuan_penerimaan->id;
                            }

                            if ($user->checkPerms($this->perms . '.approve') && in_array($pengajuan_penerimaan->status, ['waiting.approval', 'submit']) && $pengajuan_penerimaan->checkApproval()) {
                                $actions[] = [
                                    'type' => 'approval',
                                    'page' => true,
                                    'id' => $r->id,
                                    'label' => 'Approval',
                                    'icon' => 'fa fa-check text-primary',
                                    'url' => route($this->route . '.approval', $pengajuan_penerimaan->id),
                                ];
                            }

                            if ($pengajuan_penerimaan->status == 'completed' || $pengajuan_penerimaan->status == 'waiting.approval') {
                                $actions[] = 'type:print|label:Cetak|page:true|id:' . $pengajuan_penerimaan->id;
                            }

                            if ($pengajuan_penerimaan->checkAction('tracking', $this->perms)) {
                                $actions[] = [
                                    'type' => 'show',
                                    'icon' => 'fas fa-chart-line text-info',
                                    'label' => 'Tracking Approval',
                                    'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                                    'url' => route($this->route . '.tracking', $pengajuan_penerimaan->id),
                                    'id' => $pengajuan_penerimaan->id,
                                ];
                            }

                            if ($pengajuan_penerimaan->checkAction('show', $this->perms)) {
                                $actions[] = [
                                    'type' => 'show',
                                    'label' => 'Riwayat',
                                    'icon' => 'fa flaticon2-time text-success',
                                    'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                                    'url' => route($this->route . '.riwayat', $pengajuan_penerimaan->id),
                                    'id' => $pengajuan_penerimaan->id,
                                ];
                            }
                        } elseif ($pengajuan_penerimaan->status === 'rejected') {
                            if ($user->checkPerms($this->perms . '.edit')) {
                                $actions[] = [
                                    'type' => 'edit',
                                    'page' => true,
                                    'url' => route($this->route . '.edit', $pengajuan_penerimaan->id),
                                    'id' => $pengajuan_penerimaan->id,
                                ];
                            }
                        } else {
                            if ($user->checkPerms($this->perms . '.edit')) {
                                $actions[] = [
                                    'type' => 'edit',
                                    'icon' => 'fa fa-plus text-primary',
                                    'label' => 'Tambah',
                                    'page' => true,
                                    'url' => route($this->route . '.edit', $pengajuan_penerimaan->id),
                                    'id' => $pengajuan_penerimaan->id,
                                ];
                            }
                        }
                    }
                    return $this->makeDropdown($actions);
                }
            )
            ->rawColumns(['action', 'created_at', 'status', 'tanggal_pengajuan', 'nama_kandidat', 'skor', 'pengajuan', 'vendor', 'keputusan'])
            ->make(true);
    }


    public function index()
    {
        $this->setPrepare(
            [
                'tableStruct' => [
                    'datatable_1' => [
                        $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
                        $this->makeColumn('name:pengajuan|label:Pengajuan|sortable:false|className:text-center'),
                        $this->makeColumn('name:tanggal_pengajuan|label:Unit Kerja & Periode|sortable:false|className:text-center'),
                        $this->makeColumn('name:vendor|label:Vendor & Posisi|sortable:false|className:text-center'),
                        $this->makeColumn('name:nama_kandidat|label:Nama|sortable:false|className:text-center'),
                        $this->makeColumn('name:skor|label:Skor|sortable:false|className:text-center'),
                        $this->makeColumn('name:keputusan|label:Keputusan|sortable:false'),
                        $this->makeColumn('name:status|label:Status|sortable:false'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false'),
                        $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
                    ]
                ],
            ]
        );
        $VENDOR = Vendor::orderBy('nama', 'ASC')
            ->get();
        $struct = OrgStruct::where('level', '!=', 'root')
            ->get()
            ->groupBy('level');
        return $this->render(
            $this->view . '.index',
            compact('VENDOR', 'struct')
        );
    }

    public function show($id)
    {
        $record = Penerimaan::with('wawancara')->where('id', $id)->first();
        return $this->render(
            $this->view . '.show',
            compact('record')
        );
    }

    public function edit($id)
    {
        $temp = Penerimaan::find($id);
        $record = Penerimaan::with('wawancara', 'wawancara.kandidat', 'wawancara.kandidat.summary', 'wawancara.kandidat.summary.pengajuan')->where('id', $id)->first();

        return $this->render(
            $this->view . '.detail',
            compact('record')
        );
    }

    public function update(PenerimaanRequest $request, $id)
    {
        $record = Penerimaan::with('wawancara')->where('id', $id)->first();
        return $record->updateByRequest($request);
    }

    public function approval($id)
    {
        $record = Penerimaan::with('wawancara')->where('id', $id)->first();

        return $this->render(
            $this->view . '.approval',
            compact('record')
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
        $record = Penerimaan::with('wawancara')->where('id', $id)->first();
        return $record->handleApproveSave($request);
    }

    public function riwayat($id)
    {
        $record = Penerimaan::with('wawancara')->where('id', $id)->first();
        $this->setPrepare(
            [
                'title'  => 'Penerimaan | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->wawancara->kandidat->tad->nama . '</span>',
            ]
        );
        $show_title = true;
        return $this->render(
            $this->view . '.riwayat',
            compact('record', 'show_title')
        );
    }

    public function tracking($id)
    {
        $record = Penerimaan::with('wawancara')->where('id', $id)->first();
        $this->setPrepare(
            [
                'title'  => 'Penerimaan | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->wawancara->kandidat->tad->nama . '</span>',
            ]
        );
        $show_title = true;
        return $this->render(
            $this->view . '.tracking',
            compact('show_title', 'record')
        );
    }

    public function print($id)
    {
        $record = Penerimaan::with('wawancara')->where('id', $id)->first();

        $render = $this->render(
            $this->view . '.print',
            [
                'record' => $record,
            ]
        );

        $pdf = PDF::loadHTML($render)
            ->setPaper('a4')
            // ->setOptions(['isHtml5ParserEnabled' => true])
            ->setWarnings(false);

        return $pdf->stream('Penerimaan.pdf', 'Penerimaan');
    }
}
