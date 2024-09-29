<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;

class PullQuotaAktif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull:quota-aktif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ambil quota aktif dari EHC ke e-TAD';

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
        $this->output->title('Synchronizing quota aktif dari EHC ke e-TAD');
        if (env('DB_SYNC', false)) {
            DB::beginTransaction();
            try {
                QuotaPeriode::adjustQuotaByFulfillment();
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollback();
                throw $th;
            }
        }
        $this->output->title('Synchronize success.');
        return 0;
    }
}
