<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Modules\Pengajuan\Entities\Mutasi\Pegawai as DetailPengajuanMutasi;
use Modules\Pengajuan\Entities\Mutasi\Pengajuan as PengajuanMutasi;
use Modules\Pengajuan\Entities\Resign\Pegawai as DetailPengajuanResign;
use Modules\Pengajuan\Entities\Resign\Pengajuan as PengajuanResign;
use Modules\Pengajuan\Entities\Tad\Pengajuan as PengajuanTad;
use Modules\Pengajuan\Entities\Tad\Quota;
use Modules\Penilaian\Entities\Tad\Penilaian as PenilaianTad;
use Modules\Penilaian\Entities\Tad\Perpanjangan as PerpanjanganKontrak;
use Modules\Penilaian\Entities\Vendor\Penilaian as PenilaianVendor;
use Modules\Punishment\Entities\Pembinaan\Pembinaan as PembinaanTad;
use Modules\Punishment\Entities\Reward\Reward as RewardTad;

class DashboardController extends Controller
{
    protected $module = 'dashboard';
    protected $route = 'dashboard';
    protected $view  = 'dashboard::';

    public function __construct()
    {
        $this->setPrepare(
            [
                'module' => $this->module,
                'route' => $this->route,
                'view' => $this->view,
                'title' => 'Dashboard',
                'breadcrumb' => [
                    'Dashboard' =>  route($this->route . '.index'),
                ]
            ]
        );
    }

    public function index()
    {
        $pengajuan_tad = PengajuanTad::select('id')
            ->with(
                [
                    'requirement'   => function ($q) {
                        $q
                            ->select('id', 'pengajuan_id', 'jumlah')
                            ->with(
                                [
                                    'summaryKandidat' => function ($q) {
                                        $q->select('id', 'requirement_id')
                                            ->with(
                                                [
                                                    'details' => function ($q) {
                                                        $q->select('id', 'summary_id', 'tad_id')
                                                            ->with(
                                                                [
                                                                    'penerimaan' => function ($q) {
                                                                        $q->select('trans_pengajuan_tad_penerimaan.id', 'keputusan', 'trans_pengajuan_tad_penerimaan.status');
                                                                    }
                                                                ]
                                                            );
                                                    }
                                                ]
                                            );
                                    }
                                ]
                            );
                    }
                ]
            )
            ->get();
        $pengajuan_tad_completed = 0;
        foreach ($pengajuan_tad as $pengajuan) {
            $jumlah     = $pengajuan->requirement->sum('jumlah');
            $diterima   = 0;
            foreach (($pengajuan->requirement ?? []) as $requirement) {
                foreach (($requirement->summaryKandidat->details ?? []) as $kandidat) {
                    if (isset($kandidat->penerimaan->status) && $kandidat->penerimaan->status === 'completed' && $kandidat->penerimaan->keputusan === 'Diterima') {
                        $diterima += 1;
                    }
                }
            }
            if ($jumlah == $diterima) {
                $pengajuan_tad_completed += 1;
            }
        }
        $pengajuan_tad_all = PengajuanTad::count();
        $pengajuan_mutasi   = PengajuanMutasi::select('status')->get();
        $pengajuan_resign   = PengajuanResign::select('status')->get();
        $penilaian_tad      = PenilaianTad::select('status')->get();
        //
        $penilaian_vendor     = PenilaianVendor::select('status')->get();
        $perpanjangan_kontrak = PerpanjanganKontrak::select('status')->get();
        $penghargaan_tad      = RewardTad::select('status')->get();
        $pembinaan_tad        = PembinaanTad::select('status')->get();


        Artisan::call('push:personil-mutasi');
        Artisan::call('push:personil-resign');

        return $this->render(
            $this->view . 'hc',
            compact(
                'pengajuan_tad_completed',
                'pengajuan_tad_all',
                'pengajuan_mutasi',
                'pengajuan_resign',
                'penilaian_tad',
                'penilaian_vendor',
                'perpanjangan_kontrak',
                'penghargaan_tad',
                'pembinaan_tad',
            )
        );
    }

    public function quota(Request $request)
    {
        $request->merge(['quota_tahun' => $request->quota_tahun ?? date('Y')]);
        $request->merge(['quota_semester' => $request->quota_semester ?? 'Satu']);

        $quota_tahun    = $request->quota_tahun;
        $quota_semester = $request->quota_semester;
        $title = 'Quota Tahun ' . $quota_tahun . ' Semester ' . $quota_semester;

        $quota_satu = Quota::whereHas(
            'periode',
            function ($q) use ($quota_tahun) {
                $q->where('year', $quota_tahun)
                    ->where('semester', 'Satu');
            }
        )
            ->when(
                $org_struct_id = $request->quota_unit,
                function ($q) use ($org_struct_id) {
                    $q->where('org_struct_id', $org_struct_id);
                }
            );
        $quota_dua = Quota::whereHas(
            'periode',
            function ($q) use ($quota_tahun) {
                $q->where('year', $quota_tahun)
                    ->where('semester', 'Dua');
            }
        )
            ->when(
                $org_struct_id = $request->quota_unit,
                function ($q) use ($org_struct_id) {
                    $q->where('org_struct_id', $org_struct_id);
                }
            );

        return [
            'title' => ['text' => $title],
            'series' => [
                [
                    'name' => 'Quota',
                    'data' => [
                        $quota_satu->get()->sum('quota'),
                        $quota_dua->get()->sum('quota')
                    ]
                ],
                [
                    'name' => 'Pemenuhan',
                    'data' => [
                        $quota_satu->get()->sum('fulfillment'),
                        $quota_dua->get()->sum('fulfillment')
                    ]
                ],
            ],
            'xaxis' => [
                'categories' => [
                    $request->quota_tahun . ' / I',
                    $request->quota_tahun . ' / II'
                ]
            ]
        ];
    }

    public function penilaian(Request $request)
    {
        $request->merge(['penilaian_tahun'      => $request->penilaian_tahun ?? date('Y')]);
        $request->merge(['penilaian_semester'   => $request->penilaian_semester ?? 'Satu']);

        $penilaian_tahun    = $request->penilaian_tahun;
        $penilaian_semester = $request->penilaian_semester;
        $penilaian_unit     = $request->penilaian_unit;
        $title = 'Penilaian Tahun ' . $penilaian_tahun . ' Semester ' . $penilaian_semester;

        $penilaian_tad_satu     = PenilaianTad::where('status', 'completed')
            ->when(
                $penilaian_unit,
                function ($q) use ($penilaian_unit) {
                    $q->whereHas(
                        'tad.kepegawaian',
                        function ($q) use ($penilaian_unit) {
                            $q->where('cabang_id', $penilaian_unit);
                        }
                    );
                }
            )
            ->where('semester', 'Satu')
            ->where('tahun', $penilaian_tahun);
        $penilaian_tad_dua      = PenilaianTad::where('status', 'completed')
            ->when(
                $penilaian_unit,
                function ($q) use ($penilaian_unit) {
                    $q->whereHas(
                        'tad.kepegawaian',
                        function ($q) use ($penilaian_unit) {
                            $q->where('cabang_id', $penilaian_unit);
                        }
                    );
                }
            )
            ->where('semester', 'Dua')
            ->where('tahun', $penilaian_tahun);
        $penilaian_vendor_satu  = PenilaianVendor::where('status', 'completed')
            // ->when(
            //     $penilaian_unit,
            //     function ($q) use ($penilaian_unit) {
            //         $q->whereHas(
            //             'tad.kepegawaian',
            //             function ($q) use ($penilaian_unit) {
            //                 $q->where('cabang_id', $penilaian_unit);
            //             }
            //         );
            //     }
            // )
            ->where('semester', 'Satu')
            ->where('tahun', $penilaian_tahun);
        $penilaian_vendor_dua   = PenilaianVendor::where('status', 'completed')
            // ->when(
            //     $penilaian_unit,
            //     function ($q) use ($penilaian_unit) {
            //         $q->whereHas(
            //             'tad.kepegawaian',
            //             function ($q) use ($penilaian_unit) {
            //                 $q->where('cabang_id', $penilaian_unit);
            //             }
            //         );
            //     }
            // )
            ->where('semester', 'Dua')
            ->where('tahun', $penilaian_tahun);

        return [
            'title' => ['text' => $title],
            'series' => [
                [
                    'name' => 'TAD',
                    'data' => [
                        $penilaian_tad_satu->count(),
                        $penilaian_tad_dua->count()
                    ]
                ],
                [
                    'name' => 'Vendor',
                    'data' => [
                        $penilaian_vendor_satu->count(),
                        $penilaian_vendor_dua->count()
                    ]
                ],
            ],
            'xaxis' => [
                'categories' => [
                    $request->penilaian_tahun . ' / I',
                    $request->penilaian_tahun . ' / II'
                ]
            ]
        ];
    }

    public function resign(Request $request)
    {
        $request->merge(['resign_tahun'      => $request->resign_tahun ?? date('Y')]);

        $resign_tahun   = $request->resign_tahun;
        $resign_unit    = $request->resign_unit;
        $title = 'Resign Tahun ' . $resign_tahun;

        return [
            'title' => ['text' => $title],
            'series' => [
                [
                    'name' => 'Bulan',
                    'data' => [
                        PengajuanResign::where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $resign_tahun . '-01-%')
                            ->when(
                                $resign_unit,
                                function ($q) use ($resign_unit) {
                                    $q->whereHas(
                                        'employees.kepegawaian',
                                        function ($q) use ($resign_unit) {
                                            $q->where('cabang_id', $resign_unit);
                                        }
                                    );
                                }
                            )
                            ->count(),
                        PengajuanResign::where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $resign_tahun . '-02-%')
                            ->when(
                                $resign_unit,
                                function ($q) use ($resign_unit) {
                                    $q->whereHas(
                                        'employees.kepegawaian',
                                        function ($q) use ($resign_unit) {
                                            $q->where('cabang_id', $resign_unit);
                                        }
                                    );
                                }
                            )
                            ->count(),
                        PengajuanResign::where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $resign_tahun . '-03-%')
                            ->when(
                                $resign_unit,
                                function ($q) use ($resign_unit) {
                                    $q->whereHas(
                                        'employees.kepegawaian',
                                        function ($q) use ($resign_unit) {
                                            $q->where('cabang_id', $resign_unit);
                                        }
                                    );
                                }
                            )->count(),
                        PengajuanResign::where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $resign_tahun . '-04-%')
                            ->when(
                                $resign_unit,
                                function ($q) use ($resign_unit) {
                                    $q->whereHas(
                                        'employees.kepegawaian',
                                        function ($q) use ($resign_unit) {
                                            $q->where('cabang_id', $resign_unit);
                                        }
                                    );
                                }
                            )->count(),
                        PengajuanResign::where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $resign_tahun . '-05-%')
                            ->when(
                                $resign_unit,
                                function ($q) use ($resign_unit) {
                                    $q->whereHas(
                                        'employees.kepegawaian',
                                        function ($q) use ($resign_unit) {
                                            $q->where('cabang_id', $resign_unit);
                                        }
                                    );
                                }
                            )->count(),
                        PengajuanResign::where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $resign_tahun . '-06-%')
                            ->when(
                                $resign_unit,
                                function ($q) use ($resign_unit) {
                                    $q->whereHas(
                                        'employees.kepegawaian',
                                        function ($q) use ($resign_unit) {
                                            $q->where('cabang_id', $resign_unit);
                                        }
                                    );
                                }
                            )->count(),
                        PengajuanResign::where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $resign_tahun . '-07-%')
                            ->when(
                                $resign_unit,
                                function ($q) use ($resign_unit) {
                                    $q->whereHas(
                                        'employees.kepegawaian',
                                        function ($q) use ($resign_unit) {
                                            $q->where('cabang_id', $resign_unit);
                                        }
                                    );
                                }
                            )->count(),
                        PengajuanResign::where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $resign_tahun . '-08-%')
                            ->when(
                                $resign_unit,
                                function ($q) use ($resign_unit) {
                                    $q->whereHas(
                                        'employees.kepegawaian',
                                        function ($q) use ($resign_unit) {
                                            $q->where('cabang_id', $resign_unit);
                                        }
                                    );
                                }
                            )->count(),
                        PengajuanResign::where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $resign_tahun . '-09-%')
                            ->when(
                                $resign_unit,
                                function ($q) use ($resign_unit) {
                                    $q->whereHas(
                                        'employees.kepegawaian',
                                        function ($q) use ($resign_unit) {
                                            $q->where('cabang_id', $resign_unit);
                                        }
                                    );
                                }
                            )->count(),
                        PengajuanResign::where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $resign_tahun . '-10-%')
                            ->when(
                                $resign_unit,
                                function ($q) use ($resign_unit) {
                                    $q->whereHas(
                                        'employees.kepegawaian',
                                        function ($q) use ($resign_unit) {
                                            $q->where('cabang_id', $resign_unit);
                                        }
                                    );
                                }
                            )->count(),
                        PengajuanResign::where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $resign_tahun . '-11-%')
                            ->when(
                                $resign_unit,
                                function ($q) use ($resign_unit) {
                                    $q->whereHas(
                                        'employees.kepegawaian',
                                        function ($q) use ($resign_unit) {
                                            $q->where('cabang_id', $resign_unit);
                                        }
                                    );
                                }
                            )->count(),
                        PengajuanResign::where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $resign_tahun . '-12-%')
                            ->when(
                                $resign_unit,
                                function ($q) use ($resign_unit) {
                                    $q->whereHas(
                                        'employees.kepegawaian',
                                        function ($q) use ($resign_unit) {
                                            $q->where('cabang_id', $resign_unit);
                                        }
                                    );
                                }
                            )->count(),
                    ]
                ],
            ],
            'xaxis' => [
                'categories' => [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'Mei',
                    'Jun',
                    'Jul',
                    'Agu',
                    'Sep',
                    'Okt',
                    'Nov',
                    'Des',
                ]
            ]
        ];
    }

    public function mutasi(Request $request)
    {
        $request->merge(['mutasi_tahun'      => $request->mutasi_tahun ?? date('Y')]);

        $mutasi_tahun   = $request->mutasi_tahun;
        $mutasi_unit    = $request->mutasi_unit;
        $title = 'Mutasi Tahun ' . $mutasi_tahun;

        return [
            'title' => ['text' => $title],
            'series' => [
                [
                    'name' => 'Bulan',
                    'data' => [
                        PengajuanMutasi::query()
                            ->when(
                                $mutasi_unit,
                                function ($q) use ($mutasi_unit) {
                                    $q->where('unit_kerja_asal', $mutasi_unit);
                                }
                            )
                            ->where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $mutasi_tahun . '-01-%')->count(),
                        PengajuanMutasi::query()
                            ->when(
                                $mutasi_unit,
                                function ($q) use ($mutasi_unit) {
                                    $q->where('unit_kerja_asal', $mutasi_unit);
                                }
                            )
                            ->where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $mutasi_tahun . '-02-%')->count(),
                        PengajuanMutasi::query()
                            ->when(
                                $mutasi_unit,
                                function ($q) use ($mutasi_unit) {
                                    $q->where('unit_kerja_asal', $mutasi_unit);
                                }
                            )
                            ->where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $mutasi_tahun . '-03-%')->count(),
                        PengajuanMutasi::query()
                            ->when(
                                $mutasi_unit,
                                function ($q) use ($mutasi_unit) {
                                    $q->where('unit_kerja_asal', $mutasi_unit);
                                }
                            )
                            ->where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $mutasi_tahun . '-04-%')->count(),
                        PengajuanMutasi::query()
                            ->when(
                                $mutasi_unit,
                                function ($q) use ($mutasi_unit) {
                                    $q->where('unit_kerja_asal', $mutasi_unit);
                                }
                            )
                            ->where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $mutasi_tahun . '-05-%')->count(),
                        PengajuanMutasi::query()
                            ->when(
                                $mutasi_unit,
                                function ($q) use ($mutasi_unit) {
                                    $q->where('unit_kerja_asal', $mutasi_unit);
                                }
                            )
                            ->where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $mutasi_tahun . '-06-%')->count(),
                        PengajuanMutasi::query()
                            ->when(
                                $mutasi_unit,
                                function ($q) use ($mutasi_unit) {
                                    $q->where('unit_kerja_asal', $mutasi_unit);
                                }
                            )
                            ->where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $mutasi_tahun . '-07-%')->count(),
                        PengajuanMutasi::query()
                            ->when(
                                $mutasi_unit,
                                function ($q) use ($mutasi_unit) {
                                    $q->where('unit_kerja_asal', $mutasi_unit);
                                }
                            )
                            ->where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $mutasi_tahun . '-08-%')->count(),
                        PengajuanMutasi::query()
                            ->when(
                                $mutasi_unit,
                                function ($q) use ($mutasi_unit) {
                                    $q->where('unit_kerja_asal', $mutasi_unit);
                                }
                            )
                            ->where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $mutasi_tahun . '-09-%')->count(),
                        PengajuanMutasi::query()
                            ->when(
                                $mutasi_unit,
                                function ($q) use ($mutasi_unit) {
                                    $q->where('unit_kerja_asal', $mutasi_unit);
                                }
                            )
                            ->where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $mutasi_tahun . '-10-%')->count(),
                        PengajuanMutasi::query()
                            ->when(
                                $mutasi_unit,
                                function ($q) use ($mutasi_unit) {
                                    $q->where('unit_kerja_asal', $mutasi_unit);
                                }
                            )
                            ->where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $mutasi_tahun . '-11-%')->count(),
                        PengajuanMutasi::query()
                            ->when(
                                $mutasi_unit,
                                function ($q) use ($mutasi_unit) {
                                    $q->where('unit_kerja_asal', $mutasi_unit);
                                }
                            )
                            ->where('status', 'completed')->where('tgl_pengajuan', 'LIKE', $mutasi_tahun . '-12-%')->count(),
                    ]
                ],
            ],
            'xaxis' => [
                'categories' => [
                    'Jan',
                    'Feb',
                    'Mar',
                    'Apr',
                    'Mei',
                    'Jun',
                    'Jul',
                    'Agu',
                    'Sep',
                    'Okt',
                    'Nov',
                    'Des',
                ]
            ]
        ];
    }
}
