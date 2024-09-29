<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Entities\Resign\Pegawai as PegawaiResign;

class PushPersonilResign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:personil-resign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync personil resign dari e-TAD ke EHC';

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
        $this->output->title('Synchronizing personil resign dari e-TAD ke EHC');
        $PEGAWAI_RESIGN = PegawaiResign::selectRaw("*, (DATE_FORMAT(`tanggal_efektif`, '%Y%m%d') - " . now()->format('Ymd') . ") as efektif_day_count")
            ->with('pengajuan')
            ->wherehas(
                'pengajuan',
                function ($q) {
                    $q->where('status', 'completed');
                }
            )
            ->where('synced', 0)
            ->whereRaw("(DATE_FORMAT(`tanggal_efektif`, '%Y%m%d') - " . now()->format('Ymd') . ") <= 0")
            ->get();
        DB::beginTransaction();
        try {
            foreach ($PEGAWAI_RESIGN as $key => $pegawai_resign) {
                $tad            = Tad::find($pegawai_resign->tad_id);

                $kepegawaian    = Kepegawaian::find($pegawai_resign->kepegawaian_id);
                if ($kepegawaian) {
                    $kepegawaian->pengajuan_resign_pegawai_id  = $pegawai_resign->id;
                    $kepegawaian->resign_at                    = $pegawai_resign->tanggal_resign->format('d/m/Y');
                    $kepegawaian->out_at                       = $pegawai_resign->tanggal_resign->format('d/m/Y');
                    $kepegawaian->status                       = Kepegawaian::RESIGN;
                    $kepegawaian->save();

                    $tad->lock_id   = null;
                    $tad->lock_type = null;
                    $tad->save();

                    $pegawai_resign->pengajuan->_syncToEhc($tad, $kepegawaian);
                    $pegawai_resign->synced = 1;
                    $pegawai_resign->save();
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
