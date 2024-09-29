<?php

namespace Database\Seeders;

use App\Entities\EHC\User as EHCUser;
use App\Entities\Group;
use Illuminate\Database\Seeder;
use App\Entities\User;
use Modules\Master\Entities\SO\Unit;
use Modules\Master\Entities\Tad\Tad;
use Spatie\Permission\Models\Role;

class RepairEmploymentStatus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tad::repairKepegawaianStatus();
        $this->command->info('Status Kepegawaian Telah Diperbaiki.');
    }
}
