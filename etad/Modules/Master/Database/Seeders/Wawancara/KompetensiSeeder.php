<?php

namespace Modules\Master\Database\Seeders\Wawancara;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Master\Entities\Wawancara\Kompetensi;
use Modules\Master\Entities\Wawancara\Pertanyaan;

class KompetensiSeeder extends Seeder
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
                'kompetensi' => 'Impact',
                'uraian' => 'Mencipta kan suatu kesan pertama yang baik. Memancarkan rasa hormat dan menarik perhatian . Menunjukkan rasa percaya diri',
                'pertanyaan' => [
                    'Ceritakan tentang diri anda!',
                    'Apa yang anda ketahui tentang perusahaan kami?',
                    'Kenapa anda tertarik dengan perusahaan ini?',
                    'Apa kelebihan dan kekurangan anda?',
                    'Apa yang telah anda lakukan utk. Mengembangkan pengetahuan',
                ]
            ],
            [
                'kompetensi' => 'Integrity',
                'uraian' => 'Mempertahan kan  norma-norma sosial, etika dan organisasi. Memegang teguh aturan pelaksanaan dan etika.',
                'pertanyaan' => [
                    'Pengalaman apa yang anda miliki dalam bidang perbankan',
                    'Apakah anda bisa bekerja dalam satu tim?',
                    'Apakah anda bersedia kerja lembur kapan saja jika diperlukan?',
                    'Berapa gaji yang anda harapkan?',
                ]
            ],
            [
                'kompetensi' => 'Customer Focus',
                'uraian' => 'Menjadikan calon nasabah dan Kebutuhan calon nasabah tersebut sebagai fokus utamanya Mengembangkan & Mempertahankan Hubungan Nasabah Produktif',
                'pertanyaan' => [
                    'Apa itu marketing secara umum dan marketing khusus',
                    'Sebutkan jenis produk perbankan apa saja yang anda ketahui',
                    'Bagaimanakah menurut anda caranya seorang marketing perbankan ketika mencari nasabah?',
                    'Langkah apa yang akan anda tempuh bila bertemu beragam nasabah yang banyak menuntut dan cerewet ?',
                ]
            ],
        ];
        // $this->command->getOutput()->progressStart(count($data));
        $this->generate($data);
        // $this->command->getOutput()->progressFinish();
    }

    public function generate($data)
    {
        foreach ($data as $key => $item) {
            $kompetensi = Kompetensi::firstOrCreate(
                [
                    'kompetensi' => $item['kompetensi'],
                    'uraian' => $item['uraian'],
                ]
            );
            $kompetensi->pertanyaan()
                ->saveMany(
                    array_map(
                        function ($val) {
                            return new Pertanyaan(
                                [
                                    'pertanyaan' => $val,
                                ]
                            );
                        },
                        $item['pertanyaan']
                    )
                );
            // $this->command->getOutput()->progressAdvance();
        }
    }
}
