<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset data transaksi';

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
     * @return mixed
     */
    public function handle()
    {

        $this->output->title($this->description);
        try {
            // DB::beginTransaction();
            Schema::disableForeignKeyConstraints();

            DB::table('ref_tad_kepegawaian')->truncate();

            DB::table('trans_pengajuan_mutasi')->truncate();
            DB::table('trans_pengajuan_mutasi_cc')->truncate();
            DB::table('trans_pengajuan_mutasi_logs')->truncate();
            DB::table('trans_pengajuan_mutasi_pegawai')->truncate();
            DB::table('trans_pengajuan_resign')->truncate();
            DB::table('trans_pengajuan_resign_cc')->truncate();
            DB::table('trans_pengajuan_resign_logs')->truncate();
            DB::table('trans_pengajuan_resign_pegawai')->truncate();
            DB::table('trans_pengajuan_tad')->truncate();
            DB::table('trans_pengajuan_tad_cc')->truncate();
            DB::table('trans_pengajuan_tad_kandidat')->truncate();
            DB::table('trans_pengajuan_tad_logs')->truncate();
            DB::table('trans_pengajuan_tad_pewawancara')->truncate();
            DB::table('trans_pengajuan_tad_pivot')->truncate();
            DB::table('trans_pengajuan_tad_quota')->truncate();
            DB::table('trans_pengajuan_tad_quota_periode')->truncate();
            DB::table('trans_pengajuan_tad_quota_periode_logs')->truncate();
            DB::table('trans_pengajuan_tad_requirement')->truncate();
            DB::table('trans_pengajuan_tad_tl')->truncate();
            DB::table('trans_pengajuan_tad_wawancara')->truncate();
            DB::table('trans_pengajuan_tad_wawancara_penilaian')->truncate();
            DB::table('trans_penilaian_tad')->truncate();
            DB::table('trans_penilaian_tad_jawaban')->truncate();
            DB::table('trans_penilaian_tad_logs')->truncate();
            DB::table('trans_penilaian_vendor')->truncate();
            DB::table('trans_penilaian_vendor_jawaban')->truncate();
            DB::table('trans_penilaian_vendor_logs')->truncate();
            DB::table('trans_penilaian_vendor_review')->truncate();
            DB::table('trans_punishment')->truncate();
            DB::table('trans_punishment_logs')->truncate();
            DB::table('trans_reward')->truncate();
            DB::table('trans_reward_logs')->truncate();

            Schema::enableForeignKeyConstraints();
            // DB::commit();
        } catch (\Throwable $th) {
            throw $th;
        }
        $this->output->title($this->description. ' berhasil');
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
