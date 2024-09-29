<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Tad\TipeKeluarga;

class TipeKeluargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Model::unguard();
        $data = [
            [
                'tipe' => 'Ayah Kandung',
            ],
            [
                'tipe' => 'Ayah Angkat',
            ],
            [
                'tipe' => 'Ayah Tiri',
            ],
            [
                'tipe' => 'Ibu Kandung',
            ],
            [
                'tipe' => 'Ibu Angkat',
            ],
            [
                'tipe' => 'Ibu Tiri',
            ],
            [
                'tipe' => 'Saudara Kandung',
            ],
            [
                'tipe' => 'Saudara Angkat',
            ],
            [
                'tipe' => 'Saudara Tiri',
            ],
            [
                'tipe' => 'Anak Kandung',
            ],
            [
                'tipe' => 'Anak Angkat',
            ],
            [
                'tipe' => 'Anak Tiri',
            ],
        ];

        // $this->command->getOutput()->progressStart(count($data));
        $this->generate($data);
        // $this->command->getOutput()->progressFinish();
    }

    public function generate($data)
    {
        foreach ($data as $key => $item) {
            TipeKeluarga::firstOrCreate(
                [
                    'tipe' => $item['tipe']
                ]
            );
            // $this->command->getOutput()->progressAdvance();
        }
    }
}
