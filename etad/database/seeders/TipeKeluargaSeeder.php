<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
        $TIPE_KELUARGA = [
            ['tipe' => 'Ayah Kandung'],
            ['tipe' => 'Ayah Angkat'],
            ['tipe' => 'Ayah Tiri'],
            ['tipe' => 'Ibu Kandung'],
            ['tipe' => 'Ibu Angkat'],
            ['tipe' => 'Ibu Tiri'],
            ['tipe' => 'Saudara Kandung'],
            ['tipe' => 'Saudara Angkat'],
            ['tipe' => 'Saudara Tiri'],
            ['tipe' => 'Anak Kandung'],
            ['tipe' => 'Anak Angkat'],
            ['tipe' => 'Anak Tiri'],
            ['tipe' => 'Istri'],
            ['tipe' => 'Suami'],
        ];
        foreach ($TIPE_KELUARGA as $key => $item) {
            $record = TipeKeluarga::where(
                'tipe',
                $item['tipe']
            )->first();
            if (!$record) {
                $record = new TipeKeluarga;
            }
            $record->fill($item);
            $record->save();
        }
    }
}
