<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;

class CategoryPositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =
            [
                [
                    "id" => 1,
                    "name" => "Pemimpin Divisi / setingkat",
                ],
                [
                    "id" => 2,
                    "name" => "Pemimpin Cabang / setingkat",
                ],
                [
                    "id" => 3,
                    "name" => "PBO / setingkat",
                ],
                [
                    "id" => 4,
                    "name" => "Penyelia / setingkat",
                ],
                [
                    "id" => 5,
                    "name" => "Staff / setingkat",
                ],
                [
                    "id" => 6,
                    "name" => "Satpam, Pengemudi & Pramubakti",
                ],
                [
                    "id" => 7,
                    "name" => "Capeg, TKIK",
                ],
                [
                    "id" => 8,
                    "name" => "Tenaga Alih Daya (Admin)",
                ],
                [
                    "id" => 9,
                    "name" => "Tenaga Alih Daya (Non Admin)",
                ],
            ];

        // $this->command->getOutput()->progressStart(count($data));
        $this->generate($data);
        // $this->command->getOutput()->progressFinish();
    }

    public function generate($data)
    {
        foreach ($data as $key => $item) {
            KategoriPosisi::create(
                [
                    'id' =>  $item['id'],
                    'name' =>  $item['name'],
                ]
            );
            // $this->command->getOutput()->progressAdvance();
        }
    }
}
