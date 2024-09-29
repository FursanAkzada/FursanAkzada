<?php

namespace Modules\Pengajuan\Http\Controllers\Tad;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Vendor;
use Modules\Master\Entities\Wawancara\Kompetensi;
use Modules\Pengajuan\Entities\Tad\DetailKandidat;
use Modules\Pengajuan\Entities\Tad\SummaryWawancara;
use Modules\Pengajuan\Http\Requests\PengajuanWawancaraRequest;
use Yajra\DataTables\DataTables;

class PengajuanWawancaraController extends Controller
{
    protected $module   = 'pengajuan.tad.wawancara';
    protected $route    = 'pengajuan.wawancara';
    protected $view     = 'pengajuan::tad.wawancara';
    protected $perms    = 'pengajuan.tad.wawancara';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route'  => $this->route,
                'view' => $this->view,
                'perms' => $this->perms,
                'title'  => 'Wawancara',
                'breadcrumb' => [
                    'Pengajuan'     => url('pengajuan/tad/wawancara'),
                    'Wawancara' => route($this->route . '.index'),
                ]
            ]
        );
    }

    public function grid()
    {
        $user       = auth()->user();
        $user_id    = $user->id;
        $so_id      = $user->position->org_struct_id ?? null;
        $records = DetailKandidat::with('summary', 'tad', 'summary.requirement', 'summary.pengajuan')
        ->whereHas('tad', function ($query) use ($so_id, $user, $user_id) {
             $query->when(
                $personil = request()->personil,
                function ($q) use ($personil) {
                    $q->where('nama', 'like', '%' . $personil . '%');
                }
             );
        })
        ->whereHas('summary', function ($query) use ($so_id, $user, $user_id) {
            $query->where('status', 'completed')
                ->whereHas('requirement', function ($q) use ($so_id, $user, $user_id) {
                    $q->when(
                        $user->isVendor,
                        function ($qq) use ($user) {
                            $qq->whereIn('status', ['approved', 'vendor.submit', 'hc.verified', 'completed'])->where('vendor_id', $user->vendor_id);
                        }
                    )->whereHas('pengajuan', function ($qq) use ($so_id, $user, $user_id) {
                        $qq->when(
                            $user->isEhc && !$user->cekDivisiHC() && ($so_id),
                            function ($qqq) use ($so_id, $user) {
                                $qqq->where('so_id', $so_id);
				    // ->orWhereHas(
                                    // 'approvalsAll',
                                    // function ($q) use ($user){
                                    //    $q->where(
                                    //        function ($qq) use ($user){
                                    //            $qq->where('user_id', auth()->user()->id)
                                    //            ->whereIn('group_id', auth()->user()->getGroupIds());
                                    //        }
                                    //    );
                                    // }
                                // );
                            }
                        )
                        // Use a nested closure for 'orWhere' conditions
			->when($keyword = request()->keyword, function ($qqqq) use ($keyword) {
                            $qqqq->whereLike(['no_tiket', 'created_at'], $keyword);
			})
			->when($vendor_id = request()->vendor_id, function ($qqqq) use ($vendor_id) {
                    	    $qqqq->where('vendor_id', $vendor_id);
                	})
                        ->when($location_id = request()->location_id, function ($qqqq) use ($location_id) {
                            $qqqq->where('so_id', $location_id);
                        })
                        ->when($date_start = request()->date_start, function ($qqqq) use ($date_start) {
                            $qqqq->where('tgl_pengajuan', '>=', Carbon::createFromFormat('d/m/Y', $date_start)->format('Y-m-d'));
                        })
                        ->when($date_end = request()->date_end, function ($qqqq) use ($date_end) {
                            $qqqq->where('tgl_pengajuan', '<=', Carbon::createFromFormat('d/m/Y', $date_end)->format('Y-m-d'));
                        });
                    });   
                });
        })
        ->orderBy('updated_at', 'DESC')
        ->get();

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
                    return $r->tad->nama;
                }
            )
            ->editColumn(
                'skor',
                function ($r) {
                    $temp = SummaryWawancara::where('kandidat_id', $r->id)->first();
                    if ($temp != null) {
                        return $temp->details->sum('value');
                    } else {
                        return '0';
                    }
                }
            )
            ->editColumn(
                'pengajuan',
                function ($r) {
                    return $r->summary->pengajuan->no_tiket . "<br>" . $r->summary->pengajuan->tgl_pengajuan->format('d/m/Y');
                }
            )
            ->editColumn(
                'vendor',
                function ($r) {
                    return $r->summary->requirement->vendor->nama . "<br>" . $r->summary->requirement->jabatan->NM_UNIT;
                }
            )
            ->editColumn(
                'tanggal_pengajuan',
                function ($r) {
                    return '<div>' .
                        ($r->summary->pengajuan->so->name ?? '') . '<br>
                        Tahun ' . $r->summary->pengajuan->year . ' Semester ' . $r->summary->pengajuan->semester . '
                    </div>';
                }
            )
            ->editColumn(
                'status',
                function ($r) use ($user) {
                    $temp = SummaryWawancara::where('kandidat_id', $r->id)->first();
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
                    $temp = SummaryWawancara::where('kandidat_id', $r->id)->first();
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
                    $pengajuan_wawancara = SummaryWawancara::firstOrCreate([
                        'kandidat_id' => $r->id,
                    ]);
                    if ($pengajuan_wawancara != null) {
                        if (in_array($pengajuan_wawancara->status, ['draft', 'waiting.approval', 'completed'])) {
                            $actions[] = 'type:show|label:Detail|page:true|id:' . $pengajuan_wawancara->id;
                            if ($pengajuan_wawancara->checkAction('edit', $this->perms)) {
                                $actions[] = 'type:edit|page:true|id:' . $pengajuan_wawancara->id;
                            }

                            if ($user->checkPerms($this->perms . '.approve') && in_array($pengajuan_wawancara->status, ['waiting.approval', 'submit']) && $pengajuan_wawancara->checkApproval()) {
                                $actions[] = [
                                    'type' => 'show',
                                    'page' => true,
                                    'id' => $r->id,
                                    'label' => 'Approval',
                                    'icon' => 'fa fa-check text-primary',
                                    'url' => route($this->route . '.approval', $pengajuan_wawancara->id),
                                ];
                            }

                            if ($pengajuan_wawancara->status == 'completed' || $pengajuan_wawancara->status == 'waiting.approval') {
                                $actions[] = 'type:print|label:Cetak|page:true|id:' . $pengajuan_wawancara->id;
                            }

                            if ($pengajuan_wawancara->checkAction('tracking', $this->perms)) {
                                $actions[] = [
                                    'type' => 'show',
                                    'icon' => 'fas fa-chart-line text-info',
                                    'label' => 'Tracking Approval',
                                    'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                                    'url' => route($this->route . '.tracking', $pengajuan_wawancara->id),
                                    'id' => $pengajuan_wawancara->id,
                                ];
                            }

                            if ($pengajuan_wawancara->checkAction('show', $this->perms)) {
                                $actions[] = [
                                    'type' => 'show',
                                    'label' => 'Riwayat',
                                    'icon' => 'fa flaticon2-time text-success',
                                    'attrs' => 'data-modal-position="modal-dialog-centered modal-dialog-right-bottom" data-modal-size="modal-md" data-modal-backdrop="true"',
                                    'url' => route($this->route . '.riwayat', $pengajuan_wawancara->id),
                                    'id' => $pengajuan_wawancara->id,
                                ];
                            }
                        } else {
                            if ($user->checkPerms($this->perms . '.edit')) {
                                $actions[] = [
                                    'type' => 'edit',
                                    'icon' => 'fa fa-plus text-primary',
                                    'label' => 'Tambah',
                                    'page' => true,
                                    'url' => route($this->route . '.edit', $pengajuan_wawancara->id),
                                    'id' => $pengajuan_wawancara->id,
                                ];
                            }
                            if ($user->checkPerms($this->perms . '.edit')) {
                                $actions[] = [
                                    'type' => 'edit',
                                    'icon' => 'fas fa-sign-out-alt text-danger',
                                    'label' => 'Mundur',
                                    // 'page' => true,
                                    'url' => route($this->route . '.pengajuanMundur', $pengajuan_wawancara->id),
                                    'id' => $pengajuan_wawancara->id,
                                ];
                            }
                        }
                    }
                    return $this->makeDropdown($actions);
                }
            )
            ->rawColumns(['action', 'created_at', 'status', 'tanggal_pengajuan', 'nama_kandidat', 'skor', 'pengajuan', 'vendor'])
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
                        $this->makeColumn('name:status|label:Status|sortable:false'),
                        $this->makeColumn('name:created_at|label:Diperbarui|sortable:false|width:120px'),
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
        $record = SummaryWawancara::with('details', 'kandidat', 'kandidat.tad', 'kandidat.summary', 'kandidat.summary.pengajuan', 'kandidat.summary.requirement', 'kandidat.summary.details')->where('id', $id)->first();

        // generate details
        $kompetensi = Kompetensi::whereHas('pertanyaan')
            ->orderBy('kompetensi', 'desc')
            ->get();
        return $this->render(
            $this->view . '.show',
            compact('record', 'kompetensi')
        );
    }

    public function edit($id)
    {
        $temp = SummaryWawancara::find($id);

        $temp->generateDetails();
        $kompetensi = Kompetensi::whereHas('pertanyaan')
            ->orderBy('kompetensi', 'desc')
            ->get();

        $record = SummaryWawancara::with('details', 'kandidat', 'kandidat.tad', 'kandidat.summary', 'kandidat.summary.pengajuan', 'kandidat.summary.requirement', 'kandidat.summary.details')->where('id', $id)->first();

        return $this->render(
            $this->view . '.detail',
            compact('record', 'kompetensi')
        );
    }

    public function pengajuanMundur($id)
    {
        $record = SummaryWawancara::with('details', 'kandidat', 'kandidat.tad', 'kandidat.summary', 'kandidat.summary.pengajuan', 'kandidat.summary.requirement', 'kandidat.summary.details')->where('id', $id)->first();

        return $this->render(
            $this->view . '.pengajuanMundur',
            compact('record')
        );
    }

    public function handlePengajuanMundur($id, Request $request)
    {
        $record = SummaryWawancara::with('details', 'kandidat', 'kandidat.tad', 'kandidat.summary', 'kandidat.summary.pengajuan', 'kandidat.summary.requirement', 'kandidat.summary.details')->where('id', $id)->first();

        $validated = $request->validate([
            'alasan_pengunduran' => 'required',
        ]);

        return $record->handlePengajuanMundur($request);
    }

    public function update(PengajuanWawancaraRequest $request, $id)
    {
        // dd(205, $request->all());
        $record = SummaryWawancara::with('details', 'kandidat', 'kandidat.tad', 'kandidat.summary', 'kandidat.summary.pengajuan', 'kandidat.summary.requirement', 'kandidat.summary.details')->where('id', $id)->first();
        return $record->updateByRequest($request);
    }

    public function approval($id)
    {
        $record = SummaryWawancara::with('details', 'kandidat', 'kandidat.tad', 'kandidat.summary', 'kandidat.summary.pengajuan', 'kandidat.summary.requirement', 'kandidat.summary.details')->where('id', $id)->first();

        // generate details
        $kompetensi = Kompetensi::whereHas('pertanyaan')
            ->orderBy('kompetensi', 'desc')
            ->get();
        return $this->render(
            $this->view . '.approval',
            compact('record', 'kompetensi')
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
        $record = SummaryWawancara::with('details', 'kandidat', 'kandidat.tad', 'kandidat.summary', 'kandidat.summary.pengajuan', 'kandidat.summary.requirement', 'kandidat.summary.details')->where('id', $id)->first();
        return $record->handleApproveSave($request);
    }

    public function riwayat($id)
    {
        $record = SummaryWawancara::with('details', 'kandidat', 'kandidat.tad', 'kandidat.summary', 'kandidat.summary.pengajuan', 'kandidat.summary.requirement', 'kandidat.summary.details')->where('id', $id)->first();
        $this->setPrepare(
            [
                'title'  => 'Wawancara | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->kandidat->tad->nama . '</span>',
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
        $record = SummaryWawancara::with('details', 'kandidat', 'kandidat.tad', 'kandidat.summary', 'kandidat.summary.pengajuan', 'kandidat.summary.requirement', 'kandidat.summary.details')->where('id', $id)->first();
        $this->setPrepare(
            [
                'title'  => 'Wawancara | <span class="label label-xl label-danger label-inline text-nowrap">' . $record->kandidat->tad->nama . '</span>',
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
        $record = SummaryWawancara::with('details', 'kandidat', 'kandidat.tad', 'kandidat.summary', 'kandidat.summary.pengajuan', 'kandidat.summary.requirement', 'kandidat.summary.details')->where('id', $id)->first();
        // generate details
        $kompetensi = Kompetensi::whereHas('pertanyaan')
            ->orderBy('kompetensi', 'desc')
            ->get();


        $render = $this->render(
            $this->view . '.print',
            [
                'record' => $record,
                'kompetensi' => $kompetensi,
            ]
        );

        $pdf = PDF::loadHTML($render)
            ->setPaper('a4')
            // ->setOptions(['isHtml5ParserEnabled' => true])
            ->setWarnings(false);
        return $pdf->stream('Wawancara TAD.pdf', 'Wawancara TAD');
    }
}
