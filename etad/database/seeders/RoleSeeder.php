<?php

namespace Database\Seeders;

use App\Entities\Group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = ['Administrator'];

        // $this->command->getOutput()->progressStart(count($data));
        $this->generate($data);
        // $this->command->getOutput()->progressFinish();
    }

    public function generate($data)
    {
        foreach ($data as $role) {
            Group::firstOrCreate(
                [
                    'name' => $role,
                ]
            );
            Role::firstOrCreate(
                [
                    'name' => $role,
                ]
            );
            // $this->command->getOutput()->progressAdvance();
        }
    }
}
