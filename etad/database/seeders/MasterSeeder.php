<?php

namespace Database\Seeders;

use App\Entities\EHC\Jabatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Master\Entities\Jurusan;
use Modules\Master\Entities\KategoriVendor;
use Modules\Master\Entities\Pendidikan;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\TipeKeluarga;
use Modules\Master\Entities\Vendor;
use Modules\Master\Entities\VendorKategoriPivot;
use Modules\Pengajuan\Entities\Tad\Quota;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();
            // SEKOLAH = Pendidikan
            $path = database_path('seeders/json/SEKOLAH.json');
            $json = \File::get($path);
            $data = json_decode($json);
            foreach ($data->SEKOLAH as $val) {
                $record = Pendidikan::where('id', $val->sandi)
                    ->first();
                if (!$record) {
                    $record = new Pendidikan;
                    $record->id = $val->sandi;
                    $record->name = $val->lengkap;
                    $record->save();
                }
            }

            // JURUSAN


            // $this->command->info('Tipe Keluarga');
            DB::commit();
        } catch (\Throwable $th) {
            $this->command->info($th->getMessage());
            DB::rollback();
        }
    }
}
