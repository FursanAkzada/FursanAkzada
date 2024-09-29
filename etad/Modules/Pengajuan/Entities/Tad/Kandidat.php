<?php

namespace Modules\Pengajuan\Entities\Tad;

use App\Entities\BaseModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Tad\Wawancara\Penilaian;
use Modules\Pengajuan\Entities\Tad\Wawancara\Wawancara;

class Kandidat extends BaseModel
{
    use HasFactory;

    protected $table = 'trans_pengajuan_tad_kandidat';
    protected $fillable = [
        'requirement_id',
        'tad_id',
        'alasan',
        'accepted',
        'accepted_at',
        'accepted_by',
        'batch',
        'tl_id'
    ];

    public function employment()
    {
        return $this->hasOne(Kepegawaian::class, 'kandidat_id');
    }

    public function employments()
    {
        return $this->hasMany(Kepegawaian::class, 'kandidat_id');
    }

    public function requirement()
    {
        return $this->belongsTo(Requirement::class, 'requirement_id');
    }

    public function tad()
    {
        return $this->belongsTo(Tad::class, 'tad_id');
    }

    public function tl()
    {
        return $this->belongsTo(TL::class, 'tl_id');
    }

    public function wawancara()
    {
        return $this->hasOne(Wawancara::class, 'kandidat_id');
    }

    public function scopeDiterima($query)
    {
        $query->where('accepted', 1);
    }
    public function scopeDitolak($query)
    {
        $query->where('accepted', 2);
    }

    public function updateDitolakByRequest($request)
    {
        DB::beginTransaction();
        try {
            $this->tad_id = $request->kandidat_baru;
            $this->accepted = null;
            $this->accepted_at = null;
            $this->accepted_by = null;
            $this->save();

            // $this->wawancara->kesimpulan = null;
            // $this->wawancara->saran = null;
            $this->wawancara->penilaian()->delete();
            DB::table('trans_pengajuan_tad_pewawancara')
                ->where('wawancara_id', $this->wawancara->id)
                ->delete();
            $this->wawancara->interviewers()->delete();
            $this->wawancara()->delete();

            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function wawancaraCreateByRequest($request)
    {
        \DB::beginTransaction();
        try {
            if (!empty($this->wawancara)) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Data tidak bisa di input ulang <br> TAD sudah diwawancara pada tanggal <br>' . $this->wawancara->created_at->format('d/m/Y'),
                        'trace' => '',
                    ],
                    422
                );
            }

            $wawancara = $this->wawancara()->save(
                new Wawancara(
                    [
                        // 'pewawancara'   => $request->pewawancara,
                        'tgl'           => $request->tgl,
                        'kesimpulan'    => $request->kesimpulan,
                        'saran'         => $request->saran,
                    ]
                )
            );
            $wawancara->interviewers()->sync($request->pewawancara ?? []);
            // $wawancara->load('interviewers');
            // dd(107, json_decode($wawancara), $request->pewawancara);

            $pertanyaan = [];
            foreach ($request->question as $key => $item) {
                $pertanyaan[] = new Penilaian(
                    [
                        'pertanyaan_id' => $key,
                        'value' => $item
                    ]
                );
            }
            $wawancara->penilaian()->saveMany($pertanyaan);
            $this->requirement->pengajuan->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        $response = [
            'success' => true,
            'message' => 'Data successfully saved',
            'data'    => $this,
            // 'redirectTo' => $request->current_url
        ];
        // if ($request->should_redirect == 'O') {
        //     $response['redirectTo'] = $request->current_url;
        // }
        return response()->json($response);
    }

    public function __penolakanByRequest()
    {
        \DB::beginTransaction();
        $request = request();
        $query = $request->query();
        try {
            if (is_null($this->wawancara)) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Kandidat Belum mempunyai hasil wawancara, Mohon untuk isikan form wawancara terlebih dahulu',
                        'trace' => null,
                    ],
                    422
                );
            }
            $this->update(
                [
                    'accepted' => 2,
                    'accepted_at' => Carbon::now(),
                    'accepted_by' => auth()->id(),
                ]
            );
            $this->requirement->pengajuan->pivotTad()->detach();
            // $this->requirement()->update(['flag' => 'closed']);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfuly updated',
                'data'    => $this,
                // 'redirectTo' => url('pengajuan/tad/personil/' . $query['personil_id'] . '?pengajuan_id=' . $query['pengajuan_id'] . '&requirement_id=' . $query['requirement_id'] . '&kandidat_id=' . $query['kandidat_id'])
            ]
        );
    }
    public function penerimaanByRequest($request)
    {
        \DB::beginTransaction();
        try {
            // 1: Diterima
            // 2: Ditolak
            if (is_null($this->wawancara)) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Kandidat Belum mempunyai hasil wawancara, Mohon untuk isikan form wawancara terlebih dahulu',
                        'trace' => null,
                    ],
                    422
                );
            }
            if ($request->accepted == 1) {
                $requirement = $this->requirement->jumlah;
                $diterima =  Self::where(
                    [
                        'requirement_id'    => $this->requirement->id,
                        'accepted'          => 1
                    ]
                )->count();

                if ($diterima == $requirement) {
                    return response()->json(
                        [
                            'success' => false,
                            'message' => 'Kandidat diterima sudah terpenuhi sesuai kebutuhan',
                            'trace' => null,
                        ],
                        422
                    );
                }

                $kepegawaian = Kepegawaian::where('tad_id', $this->tad_id)
                    ->where('cabang_id', $this->requirement->pengajuan->so_id)
                    ->where('kandidat_id', $this->id)
                    ->first();
                if ($kepegawaian) {
                    $kepegawaian->in_at         = $request->in_at;
                    $kepegawaian->contract_due  = $request->contract_due;
                    $kepegawaian->jenis_jabatan = $request->jenis_jabatan;
                } else {
                    $kepegawaian = new Kepegawaian(
                        [
                            'status'        => Kepegawaian::WORK,
                            'tad_id'        => $this->tad_id,
                            'jabatan_id'    => $this->requirement->jabatan_id,
                            'cabang_id'     => $this->requirement->pengajuan->so_id,
                            'kandidat_id'   => $this->id,
                            'nio'           => $this->tad->generateNIO(),
                            'in_at'         => $request->in_at,
                            'contract_due'  => $request->contract_due,
                            'jenis_jabatan' => $request->jenis_jabatan
                        ]
                    );
                }
                $kepegawaian->save();
                $this->update(
                    [
                        'accepted'      => 1,
                        'alasan'        => $request->alasan,
                        'accepted_at'   => Carbon::now(),
                        'accepted_by'   => auth()->id(),
                    ]
                );

                $this->requirement->pengajuan->createLogs('kandidat.accepted', 'Kandidat '. $this->tad->nama .' Diterima. Alasan: ' . $request->alasan);
                if (Self::where(['requirement_id' => $this->requirement->id, 'accepted' => 1])->count() == $requirement) {
                    $this->requirement()->update(['flag' => 'completed']);
                    $pengajuan_id = $this->requirement->pengajuan->id;
                    $requirement_count        = Requirement::where('pengajuan_id', $pengajuan_id)->count();
                    $requirement_closed_count = Requirement::where('pengajuan_id', $pengajuan_id)
                        ->where('flag', 'completed')
                        ->count();
                    if ($requirement_count == $requirement_closed_count) {
                        Pengajuan::where('id', $pengajuan_id)->update(
                            [
                                'status'        => 'hc.verified',
                                'verified_at'   => now(),
                            ]
                        );
                        $this->requirement->pengajuan->createLogs('verified', 'Semua Kandidat telah diverifikasi Pengajuan TAD Selesai.');
                    }
                }
            } else { // ditolak
                $this->update(
                    [
                        'accepted'      => 2,
                        'alasan'        => $request->alasan,
                        'accepted_at'   => Carbon::now(),
                        'accepted_by'   => auth()->id(),
                    ]
                );
                $this->requirement->pengajuan->createLogs('kandidat.rejected', 'Kandidat '. $this->tad->nama .' Ditolak. Alasan: ' . $request->alasan);
            }
            $this->requirement->pengajuan->pivotTad()->detach();
            $this->requirement->pengajuan->updated_at = now();
            $this->requirement->pengajuan->save();

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(
                [
                    'success' => false,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ],
                500
            );
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Data successfuly updated',
                'data'    => $this,
                // 'redirectTo' => route('pengajuan.pengajuan.show', $this->requirement->pengajuan->id)
            ]
        );
    }


    public function ribbonRaw()
    {
        switch ($this->accepted) {
            case 1:
                return '<div class="ribbon-target bg-success" style="top: 20px; left: -2px;">Diterima</div>';
            case 2:
                return '<div class="ribbon-target bg-danger" style="top: 20px; left: -2px;">Ditolak</div>';
            default:
                return '<div class="ribbon-target bg-light text-dark" style="top: 20px; left: -2px;">Penentuan</div>';
        }
    }
}
