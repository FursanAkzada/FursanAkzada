<?php

namespace Modules\Pengajuan\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Modules\Master\Entities\Tad\Tad;
use Modules\Pengajuan\Notifications\NotifyEmail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tad;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tad)
    {
        $this->tad = $tad;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->tad->notify(new NotifyEmail);
    }
}
