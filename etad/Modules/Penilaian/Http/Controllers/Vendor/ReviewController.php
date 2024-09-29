<?php

namespace Modules\Penilaian\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Master\Entities\Penilaian\PertanyaanVendor;

use Modules\Master\Entities\Vendor;
use Modules\Penilaian\Entities\Vendor\Penilaian;
use Yajra\DataTables\DataTables;

class ReviewController extends Controller
{
  protected $module = 'penilaian';
  protected $route  = 'penilaian.vendor.review';
  protected $view   = 'penilaian::vendor.review';

  public function __construct()
  {
    $this->setPrepare([
      'module' => $this->module,
      'route'  => $this->route,
      'title'  => 'Review Penilaian Vendor',
      'breadcrumb' => [
        'Penilaian' => '/',
        'Vendor' => '/',
      ]
    ]);
  }

  public function grid()
  {
    $records = Penilaian::get();
    return DataTables::of($records)
      ->editColumn('num', function ($r) {
        return request()->start;
      })
      ->editColumn('vendor', function ($r) {
        return Vendor::find($r->vendor_id)->nama;
      })
      ->editColumn('status', function ($r) {
        if (!empty($r->review->user)) {
          return '<span class="badge badge-success"> Telah di review '. $r->review->user->name.'</span>';
        }
        return '<span class="badge badge-info">Belum Mengetahui</span>';
      })
      ->editColumn('created_at', function ($r) {
        return $r->createdByRaw2();
      })
      ->editColumn('action', function ($r) {
        if (empty($r->review->user)) {
          $buttons = '';
          $buttons .= $this->makeDropdown([
            'type:show|class:base-modal--render|attrs:data-modal-size="modal-lg"|label:Review Penilaian|icon:fas fa-fw fa-edit text-info|page:true|id:' . $r->id,
            // 'type:show|class:base-modal--render|attrs:data-modal-size="modal-xl"|label:Detail|page:true|id:' . $r->id,
            // 'type:edit|attrs:data-modal-size="modal-xl"|id:' . $r->id,
            // 'type:delete|id:' . $r->id . '|attrs:data-confirm-text="Hapus ' . $r['vendor'] . '?"',
          ]);
          return $buttons;
        }
      })
      ->rawColumns(['action', 'created_at', 'status'])
      ->make(true);
  }

  public function index()
  {
    $this->setPrepare([
      // 'perms' => $this->perms.'.index',
      'tableStruct' => [
        'datatable_1' => [
          $this->makeColumn('name:num|label:No|sortable:false|width:20px'),
          $this->makeColumn('name:vendor|label:Vendor|sortable:false|className:text-left'),
          $this->makeColumn('name:tahun|label:Tahun|sortable:false|className:text-center'),
          $this->makeColumn('name:status|label:Status|sortable:false|className:text-center'),
          $this->makeColumn('name:created_at|label:Diperbarui|sortable:false'),
          $this->makeColumn('name:action|label:Opsi|sortable:false|className:text-center|width:40px')
        ]
      ],
    ]);
    return $this->render($this->view . '.index');
  }


  public function create()
  {
    $data = [
      'vendor' => Vendor::get(),
      'pertanyaan' => PertanyaanVendor::get()
    ];
    return $this->render($this->view . '.create', $data);
  }


  public function store(Request $request)
  {
    $request->validate([
      'vendor_id'     => ['required'],
      'tahun'         => ['required'],
      'kesimpulan'    => ['required'],
      'kritik'        => ['required'],
      'saran'         => ['required']
    ]);

    return Penilaian::createByRequest($request);
  }

  public function review(Request $request, Penilaian $review)
  {
    $request->validate([
      'status'     => ['required']
    ]);

    return $review->reviewByRequest();
  }


  public function show(Penilaian $review)
  {

    $data = [
      'vendor' => Vendor::get(),
      'record' => $review,
      'pertanyaan' => PertanyaanVendor::with(['jawaban' => function ($query) use ($review) {
        return $query->where('penilaian_vendor_id', $review->id);
      }])->get()
    ];

    return $this->render($this->view . '.show', $data);
  }


  public function edit(Penilaian $review)
  {
    $data = [
      'vendor' => Vendor::get(),
      'record' => $review,
      'pertanyaan' => PertanyaanVendor::with(['jawaban' => function ($query) use ($review) {
        return $query->where('penilaian_vendor_id', $review->id);
      }])->get()
    ];

    return $this->render($this->view . '.edit', $data);
  }


  public function destroy(Penilaian $review)
  {
    return $review->deleteByRequest();
  }
}
