<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class StructureOrganizationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProvinceTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(DistrictTableSeeder::class);
        $this->call(OrgStructTableSeeder::class);
        $this->call(CategoryPositionsSeeder::class);
    }
}
