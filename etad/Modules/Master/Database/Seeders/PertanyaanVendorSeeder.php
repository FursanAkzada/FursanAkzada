<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Entities\Pertanyaan\Vendor;

class PertanyaanVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'pertanyaan' => 'Ketaatan Pada PKS(Perjanjian Kerjasama) dan Undang-Undang yang berlaku'
            ],
            [
                'id' => 2,
                'pertanyaan' => 'Tertib Administrasi'
            ],
            [
                'id' => 3,
                'pertanyaan' => 'Kualitas pelayanan dan kemampuan merespon komplain'
            ],
            [
                'id' => 4,
                'pertanyaan' => 'Kemampuan memberikan solusi pada setiap permasalahan yang terjadi'
            ],
            [
                'id' => 5,
                'pertanyaan' => 'Kesediaan dalam memberitahukan perubahan aturan terkait ketenagakerjaan'
            ],
            [
                'id' => 6,
                'pertanyaan' => 'Ketepatan jumlah & waktu pembayaran gaji tenaga alih daya'
            ],
            [
                'id' => 7,
                'pertanyaan' => 'Kesesuaian tagihan per hari kerja (proporsonal bagi tenaga baru)'
            ],
            [
                'id' => 8,
                'pertanyaan' => 'Kualitas SDM tenaga alih daya yang disediakan oleh Vendor'
            ],
            [
                'id' => 9,
                'pertanyaan' => 'Melakukan evaluasi tenaga alih daya yang dikelola vendor tersebut setiap 4 bulan sekali'
            ],
            [
                'id' => 10,
                'pertanyaan' => 'Pelatihan bagi tenaga alih daya yang dikelola Vendor'
            ],
        ];

        // $this->command->getOutput()->progressStart(count($data));
        $this->generate($data);
        // $this->command->getOutput()->progressFinish();
    }

    public function generate($data)
    {
        // \DB::unprepared('SET IDENTITY_INSERT ref_penilaian_vendor ON');
        foreach ($data as $key => $item) {
            Vendor::firstOrCreate(
                [
                    // 'id' =>  $item['id'],
                    'pertanyaan' =>  $item['pertanyaan']
                ]
            );
            // $this->command->getOutput()->progressAdvance();
        }
        // \DB::unprepared('SET IDENTITY_INSERT ref_penilaian_vendor OFF');
    }
}
