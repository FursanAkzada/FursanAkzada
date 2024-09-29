<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\SO\Positions;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->saveJabatan();
    }

    public function saveJabatan()
    {
        $jabatans = [
            [
                'name'          => 'Direktur Utama',
                'org_struct'    => "Direktur Utama",
            ],
            [
                'name'          => 'Pimpinan Divisi',
                'org_struct'    => "Divisi Human Capital",
            ],
            [
                'name'          => 'Pimpinan Divisi',
                'org_struct'    => "Divisi Hukum",
            ],
            [
                'name'          => 'Pimpinan Divisi',
                'org_struct'    => "Divisi Pengendalian Keuangan",
            ],
            [
                'name'          => 'Pimpinan Divisi',
                'org_struct'    => "Divisi Teknologi Informasi",
            ],
            [
                'name'          => 'Kepala Cabang Utama',
                'org_struct'    => "Cabang Utama",
            ],
            [
                'name'          => 'Kepala Cabang Madiun',
                'org_struct'    => "Cabang Madiun",
            ],
            //
            [
                'name'          => 'Junior Officer HCIS dan Dapeg',
                'org_struct'    => "Divisi Human Capital",
            ],
            [
                'name'          => 'Junior Officer Rekrutmen',
                'org_struct'    => "Divisi Human Capital",
            ],
            [
                'name'          => 'Officer Corporate Culture',
                'org_struct'    => "Divisi Human Capital",
            ],
            [
                'name'          => 'Funding Officer',
                'org_struct'    => "Divisi Human Capital",
            ],
            [
                'name'          => 'Junior Officer IT Data Center',
                'org_struct'    => "Divisi Teknologi Informasi",
            ],
            [
                'name'          => 'Funding Officer',
                'org_struct'    => "Cabang Batu",
            ],
            [
                'name'          => 'Staf HC',
                'org_struct'    => "Cab. Utama",
            ],
            [
                'name'          => 'Pemimpin Cabang Utama',
                'org_struct'    => "Cab. Utama",
            ],
            [
                'name'          => 'Staf HC',
                'org_struct'    => "Cab. Utama",
            ],
            [
                'name'          => 'Pemimpin Cabang Utama',
                'org_struct'    => "Cab. Utama",
            ],
            [
                'name'          => 'Admin HC Rekrutmen',
                'org_struct'    => "Divisi Human Capital",
            ],
            [
                'name'          => 'AVP Operasional',
                'org_struct'    => "Divisi Human Capital",
            ],
            [
                'name'          => 'VP Human Capital',
                'org_struct'    => "Divisi Human Capital",
            ],
            [
                'name'          => 'Staf HC',
                'org_struct'    => "Cab. Trenggalek",
            ],
            [
                'name'          => 'Kepala Cabang Trenggalek',
                'org_struct'    => "Cab. Trenggalek",
            ],
        ];

        foreach ($jabatans as $val) {
            $org_struct = OrgStruct::where('name', $val['org_struct'])->first();
            if (!$org_struct) {
                continue;
            }
            $position = Positions::where('name', $val['name'])->first();
            if (!$position) {
                $position = new Positions;
            }
            $position->org_struct_id    = $org_struct->id;
            $position->name             = $val['name'];
            $position->status           = 1;
            $position->save();
        }
    }
}
