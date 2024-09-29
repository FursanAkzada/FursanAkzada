<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Database\Seeders\AlasanResignSeeder;

use Modules\Master\Database\Seeders\CityTableSeeder;
use Modules\Master\Database\Seeders\OrgStructTableSeeder;
use Modules\Master\Database\Seeders\PertanyaanTadSeeder;
use Modules\Master\Database\Seeders\PertanyaanVendorSeeder;
use Modules\Master\Database\Seeders\PositionSeeder;
use Modules\Master\Database\Seeders\ProvinceTableSeeder;
use Modules\Master\Database\Seeders\Wawancara\KompetensiSeeder;
use Modules\Settings\Database\Seeders\MenuFlowSeeder;
use Modules\Settings\Database\Seeders\PermissionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(ProvinceTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(OrgStructTableSeeder::class);
        $this->call(CabangSeeder::class);
        $this->call(CapemSeeder::class);
        $this->call(SeksiBagianSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(MasterSeeder::class);
        $this->call(JurusanSeeder::class);
        $this->call(TipeKeluargaSeeder::class);
        $this->call(KategoriVendorSeeder::class);
        $this->call(QuotaPeriodeSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(KompetensiSeeder::class);
        $this->call(PertanyaanVendorSeeder::class);
        $this->call(PertanyaanTadSeeder::class);

        $this->call(MenuFlowSeeder::class);
        $this->call(SqlsrvSeeder::class);
        $this->call(AlasanResignSeeder::class);
    }
}
