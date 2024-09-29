<?php

namespace App\Console\Commands;

use App\Entities\EHC\Jabatan;
use App\Entities\Record;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Tes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tes';

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
        $record = new Record();
        $record->key = 'time';
        $record->value = now()->format('Y-m-d h:m:s');
        $record->save();
        $this->output->info('tes ' . $record->value);
        return 0;
    }
}
