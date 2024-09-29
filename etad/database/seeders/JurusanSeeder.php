<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Entities\Jurusan;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $JURUSAN = [
            [
                'pendidikan_id' => 7,
                'name' => 'Marketing'
            ],
            [
                'pendidikan_id' => 7,
                'name' => 'Manajemen'
            ],
            [
                'pendidikan_id' => 7,
                'name' => 'Manajemen Informasi'
            ],
            [
                'pendidikan_id' => 1,
                'name' => 'IPA'
            ],
            [
                'pendidikan_id' => 3,
                'name' => 'Pariwisata'
            ],
            [
                'pendidikan_id' => 3,
                'name' => 'Administrasi Kantor'
            ],
        ];

        foreach ($JURUSAN as $key => $item) {
            $record = Jurusan::firstOrCreate(['name' => $item['name'], 'pendidikan_id' => $item['pendidikan_id']]);
        }
    }
}
