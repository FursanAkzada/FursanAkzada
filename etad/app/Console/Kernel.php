<?php

namespace App\Console;

use Illuminate\Support\Carbon;
use Modules\Master\Entities\Tad\Tad;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Pengajuan\Entities\Resign\Pengajuan;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $pengajuanList = Pengajuan::where('status', 'completed')->get();

            foreach ($pengajuanList as $pengajuan) {
                $details = $pengajuan->pegawai;

                foreach ($details as $pegawai) {
                    $personil_tad = Tad::with('kepegawaian')->find($pegawai->kepegawaian->tad_id);
                    if (now()->isSameDay(Carbon::createFromFormat('Y-m-d', $pegawai->pivot->tanggal_efektif))) {
                        if (empty($personil_tad->lock_type) && empty($personil_tad->lock_id)) {
                            $personil_tad->lock_id      = $pegawai->pivot->id;
                            $personil_tad->lock_type    = Modules\Pengajuan\Entities\Resign\Pegawai::class;
                            $personil_tad->save();
                        }
                    }
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
