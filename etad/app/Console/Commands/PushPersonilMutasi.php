<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Mutasi\Pegawai as PegawaiMutasi;
use Modules\Pengajuan\Entities\Tad\Quota;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;

class PushPersonilMutasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:personil-mutasi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync personil mutasi dari e-TAD ke EHC';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->output->title('Synchronizing personil mutasi dari e-TAD ke EHC');
        $PEGAWAI_MUTASI = PegawaiMutasi::selectRaw("*, (DATE_FORMAT(`tgl_efektif`, '%Y%m%d') - " . now()->format('Ymd') . ") as efektif_day_count")
            ->with('pengajuan')
            ->wherehas(
                'pengajuan',
                function ($q) {
                    $q->where('status', 'completed');
                }
            )
            ->where('synced', 0)
            ->whereRaw("(DATE_FORMAT(`tgl_efektif`, '%Y%m%d') - " . now()->format('Ymd') . ") <= 0")
            ->get();
        DB::beginTransaction();
        try {
            foreach ($PEGAWAI_MUTASI as $key => $pegawai_mutasi) {
                $tad                            = Tad::with('kepegawaian')->find($pegawai_mutasi->tad_id);
                // $tad->kepegawaian->status       = Kepegawaian::MUTATION;
                if ($tad->kepegawaian) {
                    $tad->kepegawaian->update(
                        [
                            'out_at'        => $pegawai_mutasi->tgl_efektif->format('d/m/Y'),
                            'mutation_at'   => $pegawai_mutasi->tgl_efektif->format('d/m/Y'),
                        ]
                    );

                    $new_employment = new Kepegawaian;
                    $new_employment->status                         = Kepegawaian::MUTATION;
                    $new_employment->year                           = $tad->kepegawaian->year;
                    $new_employment->semester                       = $tad->kepegawaian->semester;
                    $new_employment->previous_employment_id         = $tad->kepegawaian->id;
                    $new_employment->tad_id                         = $tad->id;
                    $new_employment->jabatan_id                     = $tad->kepegawaian->jabatan_id;
                    $new_employment->vendor_id                      = $tad->kepegawaian->vendor_id;
                    $new_employment->nio                            = $tad->kepegawaian->nio;
                    $new_employment->cabang_id                      = $pegawai_mutasi->pengajuan->unit_kerja_tujuan;
                    $new_employment->kandidat_id                    = $tad->kepegawaian->kandidat_id;
                    $new_employment->pengajuan_mutasi_pegawai_id    = $pegawai_mutasi->id;
                    $new_employment->jenis_jabatan                  = $tad->kepegawaian->jenis_jabatan;
                    $new_employment->in_at                          = now()->format('d/m/Y');
                    $new_employment->resign_at                      = null;
                    $new_employment->out_at                         = null;
                    if ($tad->kepegawaian->contract_due) {
                        $new_employment->contract_due                   = $tad->kepegawaian->contract_due->format('d/m/Y');
                    }
                    $new_employment->save();

                    // dd(
                    //     json_encode(json_decode($tad->kepegawaian)),
                    //     json_encode(json_decode($new_employment)),
                    // );

                    $tad                    = Tad::find($pegawai_mutasi->tad_id);
                    $tad->kepegawaian_id    = $new_employment->id;
                    $tad->lock_id           = null;
                    $tad->lock_type         = null;
                    $tad->save();
                    $kepegawaian    = Kepegawaian::find($pegawai_mutasi->kepegawaian_id);
                    $pegawai_mutasi->pengajuan->_syncToEhc($tad, $new_employment);
                    $pegawai_mutasi->synced = 1;
                    $pegawai_mutasi->save();

                    // KURANGI PEMENUHAN QUOTA LAMA
                    $quota_periode_lama = QuotaPeriode::query()
                        ->where('level', $pegawai_mutasi->pengajuan->unitKerjaAsal->level)
                        ->where('year', $kepegawaian->year ?? now()->format('Y'))
                        ->where('semester', $kepegawaian->semester == 'Dua' ? 'Dua' : 'Satu')
                        ->first();

		    if ($quota_periode_lama){
                    $quota_periode_lama->fulfillment -= 1;
                    $quota_periode_lama->save();
		    }

		    if ($quota_periode_lama){
                    	$quota_lama = Quota::where('pengajuan_tad_quota_periode_id', $quota_periode_lama->id)
                        	->where('org_struct_id', $kepegawaian->cabang_id)
                        	->where('posisi_tad_id', $kepegawaian->jabatan_id)
                        	->first();

			if ($quota_lama){
                    	$quota_lama->fulfillment -= 1;
                    	$quota_lama->save();
			}
		    }

                    // TAMBAH PEMENUHAN QUOTA BARU
                    $quota_periode_baru = QuotaPeriode::where('level', $pegawai_mutasi->pengajuan->unitKerjaTujuan->level)
                        ->where('year', now()->format('Y'))
                        ->where('semester', now()->format('m') <= 6 ? 'Satu' : 'Dua')
                        ->first();

		    if ($quota_periode_baru){
                    $quota_periode_baru->fulfillment += 1;
                    $quota_periode_baru->save();
		    }

		    if ($quota_periode_baru){
                    	$quota_baru = Quota::where('pengajuan_tad_quota_periode_id', $quota_periode_baru->id)
                        	->where('org_struct_id', $new_employment->cabang_id)
                        	->where('posisi_tad_id', $new_employment->jabatan_id)
                        	->first();

			if ($quota_baru){
                    	$quota_baru->fulfillment += 1;
                    	$quota_baru->save(); 
			}
		    }
                }
            }
            DB::commit();
            $this->output->title('Synchronize success.');
            return 0;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
