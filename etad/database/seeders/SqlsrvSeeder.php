<?php

namespace Database\Seeders;

use App\Entities\EHC\Agama;
use App\Entities\EHC\Cabang;
use App\Entities\EHC\Datapegawai;
use App\Entities\EHC\Divisi;
use App\Entities\EHC\Jabatan;
use App\Entities\EHC\JenisPunishment;
use App\Entities\EHC\Pendidikan;
use App\Entities\EHC\Tad;
use App\Entities\EHC\User as EHCUser;
use App\Entities\EHC\VDataPegawai;
use App\Entities\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Spatie\Permission\Models\Role;

class SqlsrvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        // // M_LOGIN User EHC
        // $path = database_path('seeders/json/M_LOGIN.json');
        // $json = \File::get($path);
        // $data = json_decode($json);
        // // $this->command->info('M_LOGIN');
        // // $this->command->getOutput()->progressStart(count($data));
        // foreach ($data as $val) {
        //     $record = EHCUser::where('KD_LOG', $val->KD_LOG)
        //         ->first();
        //     if (!$record) {
        //         $record = new EHCUser((array) $val);
        //         $record->save();
        //     }
        //     // $this->command->getOutput()->progressAdvance();
        // }
        // AGAMA
        $path = database_path('seeders/json/AGAMA.json');
        $json = \File::get($path);
        $data = json_decode($json);
        foreach ($data->AGAMA as $val) {
            $record = Agama::where('Sandi', $val->Sandi)
                ->first();
            if (!$record) {
                $record = new Agama((array) $val);
                $record->save();
            }
        }
        if (env('DB_SEED_EHC', false)) {
            // DIVISI
            $path = database_path('seeders/json/DIVISI.json');
            $json = \File::get($path);
            $data = json_decode($json);
            foreach ($data->DIVISI as $val) {
                $record = Divisi::where('Sandi', $val->Sandi)
                    ->first();
                if (!$record) {
                    $record = new Divisi((array) $val);
                    // $record->city_id = 264;
                    // $record->province_id = 15;
                    $record->save();
                }
            }
        }
        // SEKOLAH = Pendidikan
        $path = database_path('seeders/json/SEKOLAH.json');
        $json = \File::get($path);
        $data = json_decode($json);
        foreach ($data->SEKOLAH as $val) {
            $record = Pendidikan::where('sandi', $val->sandi)
                ->first();
            if (!$record) {
                $record = new Pendidikan((array) $val);
                $record->save();
            }
        }
        // TBL_DAPEG_OUTSOURCING = Tad
        // $path = database_path('seeders/json/TBL_DAPEG_OUTSOURCING.json');
        // $json = \File::get($path);
        // $data = json_decode($json);
        // $this->command->info('TBL_DAPEG_OUTSOURCING');
        // $this->command->getOutput()->progressStart(count($data));
        // foreach ($data as $val) {
        //     $record = Tad::where('NO', $val->NO)->first();
        //     if (!$record) {
        //         $record = new Tad((array) $val);
        //         $record->save();
        //     }
        //     $this->command->getOutput()->progressAdvance();
        // }
        // $this->command->getOutput()->progressFinish();
        // TBL_UNITKERJA = Jabatan
        $path = database_path('seeders/json/TBL_UNITKERJA.json');
        $json = \File::get($path);
        $data = json_decode($json);
        foreach ($data as $val) {
            $record = Jabatan::where('idunit', $val->idunit)
                ->first();
            if (!$record) {
                $record = new Jabatan((array) $val);
                $record->kategori_id    = 1;
                $record->jenis          = 'J-901';
                $record->save();
            }
        }
        // // V_DATAPEGAWAI
        // $path = database_path('seeders/json/V_DATAPEGAWAI.json');
        // $json = \File::get($path);
        // $data = json_decode($json);
        // foreach ($data as $val) {
        //     $record = VDataPegawai::where('nip', $val->nip)
        //         ->first();
        //     if (!$record) {
        //         $record = new VDataPegawai((array) $val);
        //         $record->save();
        //     }
        // }
        // datapegawai
        if (env('DB_SEED_EHC', false)) {
            $path = database_path('seeders/json/datapegawai.json');
            $json = \File::get($path);
            $data = json_decode($json);
            foreach ($data as $val) {
                $record = Datapegawai::where('nip', $val->nip)
                    ->first();
                if (!$record) {
                    $record = new Datapegawai((array) $val);
                    $record->save();
                }
            }
        }
        // JenisPunishment
        $path = database_path('seeders/json/Jenis_Reward.json');
        $json = \File::get($path);
        $data = json_decode($json);
        foreach ($data as $val) {
            $record = JenisPunishment::where('sandi', $val->sandi)
                ->first();
            if (!$record) {
                $record = new JenisPunishment((array) $val);
                $record->save();
            }
        }
        // JABATAN
        $path = database_path('seeders/json/JABATAN.json');
        $json = \File::get($path);
        $data = json_decode($json);
        foreach ($data->JABATAN as $val) {
            $record = DB::table('ref_ehc_jabatan')
                ->where('sandi', $val->sandi)
                ->first();
            // dd($val, $record);
            if (!$record) {
                DB::table('ref_ehc_jabatan')->insert(
                    (array) $val
                );
            }
        }
        if (env('DB_SEED_EHC', false)) {
            // CABANG
            $path = database_path('seeders/json/CABANG.json');
            $json = \File::get($path);
            $data = json_decode($json);
            foreach ($data->CABANG as $val) {
                $record = DB::table('ref_ehc_cabang')
                    ->where('Sandi', $val->Sandi)
                    ->first();
                if (!$record) {
                    DB::table('ref_ehc_cabang')->insert(
                        (array) $val
                    );
                }
                // $record = DB::table('ref_ehc_cabang')
                //     ->where('Sandi', $val->Sandi)
                //     ->first();
                // $record->city_id = 264;
                // $record->province_id = 15;
                // $record->save();
            }
            // CABCPM
            $path = database_path('seeders/json/CABCPM.json');
            $json = \File::get($path);
            $data = json_decode($json);
            foreach ($data->CABCPM as $val) {
                $record = DB::table('ref_ehc_capem')
                    ->where('KDCAPEM', $val->KDCAPEM)
                    ->first();
                if (!$record) {
                    DB::table('ref_ehc_capem')->insert(
                        (array) $val
                    );
                }
                // $record = DB::table('ref_ehc_capem')
                //     ->where('KDCAPEM', $val->KDCAPEM)
                //     ->first();
                // $record->city_id = 264;
                // $record->province_id = 15;
                // $record->save();
            }
            if (env('DB_SEED_EHC', false)) {
                // PJUMLAH_CUTI_IJIN
                $path = database_path('seeders/json/PJUMLAH_CUTI_IJIN.json');
                $json = \File::get($path);
                $data = json_decode($json);
                foreach ($data->PJUMLAH_CUTI_IJIN as $val) {
                    $record = DB::table('ref_ehc_pjumlah_cuti_ijin')
                        ->where('nip', $val->nip)
                        ->first();
                    if (!$record) {
                        DB::table('ref_ehc_pjumlah_cuti_ijin')->insert(
                            (array) $val
                        );
                    }
                }
                // PYL_NO_REK
                $path = database_path('seeders/json/PYL_NO_REK.json');
                $json = \File::get($path);
                $data = json_decode($json);
                foreach ($data->PYL_NO_REK as $val) {
                    $record = DB::table('ref_ehc_pyl_no_rek')
                        ->where('Nip', $val->Nip)
                        ->first();
                    if (!$record) {
                        DB::table('ref_ehc_pyl_no_rek')->insert(
                            (array) $val
                        );
                    }
                }
            }
            // SEKSI
            $path = database_path('seeders/json/SEKSI.json');
            $json = \File::get($path);
            $data = json_decode($json);
            foreach ($data->SEKSI as $val) {
                $record = DB::table('ref_ehc_seksi')
                    ->where('SANDI', $val->SANDI)
                    ->first();
                if (!$record) {
                    DB::table('ref_ehc_seksi')->insert((array) $val);
                }
                // $record = DB::table('ref_ehc_seksi')
                //     ->where('SANDI', $val->SANDI)
                //     ->first();
                // $record->city_id = 264;
                // $record->province_id = 15;
                // $record->save();
            }
            // SeksiBagian
            $path = database_path('seeders/json/SeksiBagian.json');
            $json = \File::get($path);
            $data = json_decode($json);
            foreach ($data->SeksiBagian as $val) {
                $record = DB::table('ref_ehc_seksi_bagian')
                    ->where('CodeSB', $val->CodeSB)
                    ->first();
                if (!$record) {
                    DB::table('ref_ehc_seksi_bagian')->insert((array) $val);
                }
                // $record = DB::table('ref_ehc_seksi_bagian')
                //     ->where('CodeSB', $val->CodeSB)
                //     ->first();
                // $record->city_id = 264;
                // $record->province_id = 15;
                // $record->save();
            }

            if (env('DB_SEED_EHC', false)) {
                // PYL_GRADESAL
                $path = database_path('seeders/json/PYL_GRADESAL.json');
                $json = \File::get($path);
                $data = json_decode($json);
                foreach ($data->PYL_GRADESAL as $val) {
                    $record = DB::table('ref_ehc_pyl_gradesal')
                        ->where('NIP', $val->NIP)
                        ->first();
                    if (!$record) {
                        DB::table('ref_ehc_pyl_gradesal')->insert((array) $val);
                    }
                }
            }
        }
    }
}
