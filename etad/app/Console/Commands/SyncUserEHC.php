<?php

namespace App\Console\Commands;

use App\Entities\EHC\User as EHCUser;
use App\Entities\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncUserEHC extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync User EHC to SIS e-TAD';

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

        $this->output->title('Synchronizing EHC User to SIS e-TAD ..');
        $this->generate(EHCUser::whereIn('STA_LOG', [2, 4, 10, 0])->get());
        $this->output->title('Synchronize success.');
    }

    public function generate($data)
    {
        $bar = $this->output->createProgressBar($data->count());
        $bar->start();
        ini_set("memory_limit", -1);
        DB::beginTransaction();
        try {
            foreach ($data as $key => $item) {
                $user = User::firstOrNew(['username' => $item->USER_LOG]);
                $user->fill([
                    'password' => $item->PASS_LOG,
                    'user_type' => 'ehc',
                    'kd_log' => $item->KD_LOG,
                    'name' => $item->NM_USER,
                    'email' => $item->USER_LOG . '@email.com',
                ]);
                $user->save();
                $bar->advance();
            }
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e->getMessage());
        }
        DB::commit();
        $bar->finish();
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
