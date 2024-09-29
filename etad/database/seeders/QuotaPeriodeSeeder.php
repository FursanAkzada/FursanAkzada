<?php

namespace Database\Seeders;

use App\Entities\EHC\Jabatan;
use Illuminate\Database\Seeder;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Pengajuan\Entities\Tad\Quota;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;

class QuotaPeriodeSeeder extends Seeder
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
        $QOUTA_PERIODE = [
            [
                'year' => 2023,
                'semester' => 'Dua',
                'level' => 'bod',
                'status' => 'draft',
            ],
            [
                'year' => 2023,
                'semester' => 'Dua',
                'level' => 'vice',
                'status' => 'draft',
            ],
            [
                'year' => 2023,
                'semester' => 'Dua',
                'level' => 'division',
                'status' => 'draft',
            ],
            [
                'year' => 2023,
                'semester' => 'Dua',
                'level' => 'departemen',
                'status' => 'draft',
            ],
            [
                'year' => 2023,
                'semester' => 'Dua',
                'level' => 'cabang',
                'status' => 'draft',
            ],
            [
                'year' => 2023,
                'semester' => 'Dua',
                'level' => 'capem',
                'status' => 'draft',
            ],
            [
                'year' => 2023,
                'semester' => 'Dua',
                'level' => 'kas',
                'status' => 'draft',
            ],
        ];

        $JABATAN = Jabatan::select('idunit')->get();
        foreach ($QOUTA_PERIODE  as $key => $value) {
            $record = QuotaPeriode::firstOrNew(
                [
                    'year'      => $value['year'],
                    'semester'  => $value['semester'],
                    'level'     => $value['level']
                ]
            );
            $record->status = $value['status'];
            $record->save();
            $structs = OrgStruct::select('id')
                ->where('level', $value['level'])
                ->get();
            foreach ($structs as $struct) {
                foreach ($JABATAN as $jabatan) {
                    $quota = Quota::where('pengajuan_tad_quota_periode_id', $record->id)
                        ->where('org_struct_id', $struct->id)
                        ->where('posisi_tad_id', $jabatan->idunit)
                        ->first();
                    if (!$quota) {
                        $quota = new Quota;
                        $quota->pengajuan_tad_quota_periode_id = $record->id;
                        $quota->org_struct_id   = $struct->id;
                        $quota->posisi_tad_id   = $jabatan->idunit;
                        $quota->quota           = 0;
                        $quota->save();
                    }
                }
            }
        }
    }
}
