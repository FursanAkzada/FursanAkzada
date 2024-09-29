<?php

namespace Modules\Master\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Master\Entities\Pertanyaan\Tad;

class PertanyaanTadSeeder extends Seeder
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
                "parent_id" => null,
                "judul" => "Soft Competency",
                "childs" => [
                    [
                        "judul" => 'Excellence',
                        "urut" => 1,
                        "pertanyaan" => "Mampu melakukan perbaikan diri, menunjukkan rasa percaya diri, menarik perhatian, menciptakan kesan pertama yang menarik"
                    ],
                    [
                        "judul" => "Profesional",
                        "urut" => 2,
                        "pertanyaan" => "Melaksanakan serta menguasai tugas & tanggungjawabnya dengan berkomitmen mencapai hasil yang optimal, bertanggung jawab"
                    ],
                    [
                        "judul" => "Integritas",
                        "urut" => 3,
                        "pertanyaan" => "Konsisten dalam pemikiran dan perilaku serta jujur baik pada diri sendiri maupun orang lain berdasarkan nilai-nilai etika, jujur, disiplin dan terpuji dalam pikiran lisan dan tindakan"
                    ],
                    [
                        "judul" => "Synergi",
                        "urut" => 4,
                        "pertanyaan" => "Kerjasama yang saling menguntungkan dengan komitmen untuk meningkatkan nilai tambah bagi kedua belah pihak, saling menghargal perbedaan dan mau berbagi untuk kepentingan perusahaan"
                    ],
                    [
                        "judul" => "Innovation",
                        "urut" => 5,
                        "pertanyaan" => "Kemampuan untuk menghasilkan ide/pemikiran baru atau menjadikan sesuatu yang telah ada menjadi lebih baik, memiliki keinginan untuk meningkatkan kompetensi, kemampuan utuk terus menerus belajar, menerapkan hasil pembelajaran dalam kondisi baru untuk mencapai hasl yang diinginkan"
                    ],
                ]
            ],
            /*  */
            [
                "parent_id" => null,
                "judul" => "Aspek Teknis Pekerjaan",
                "childs" => [
                    [
                        "judul" => "Efektifitas & Efisiensi Kerja",
                        "urut" => 1,
                        "pertanyaan" => "Mampu untuk melakukan pekerjaan pekerjaan yang menjadi kewajibannya dan mengerjakan pekerjaan lain yang ditugaskan sesuai dengan waktu yang sudah ditetapkan"
                    ],
                    [
                        "judul" => "Ketepatan waktu dalam menyelesaikan tugas",
                        "urut" => 2,
                        "pertanyaan" => "Kemampuan untuk mengerjakan tugas-tugas yang diberikan sesuai dengan jadwal yang sudah ditetapkan dengan tepat dan benar"
                    ],
                    [
                        "judul" => "Kemampuan mencapai target / standar perusahaan",
                        "urut" => 3,
                        "pertanyaan" => "Kemampuan untuk bekerja sesuai dengan standar/veterduan yang berlaku dan dapat mencapai target yang telah ditentukan"
                    ],
                ]
            ],

            /*  */
            [
                "parent_id" => null,
                "judul" => "Aspek Non Teknis",
                "childs" => [
                    [
                        "judul" => "Kedisiplinan",
                        "urut" => 1,
                        "pertanyaan" => "Bekerja sesuai dengan Standar Operational Prosedure, datang dan pulang kerja sesuai dengan jam kerja yang telah ditentukan, berpenampilan sesuai dengan standar yang telah ditentukan"
                    ],
                    [
                        "judul" => "Tanggung jawab & Loyalitas",
                        "urut" => 2,
                        "pertanyaan" => "Menyelesaikan pekerjaan-pekerjaan yang ditugaskan maupun yang tidak ditugaskan. dengan baik, membantu rekan kerja untuk menyelesaikan tugas-tugasnya"
                    ],
                    [
                        "judul" => "Pelayanan",
                        "urut" => 3,
                        "pertanyaan" => "Memberi pelayanan kepada nasabah, atasan, rekan kerja sesuai dengan standar layanan yang telah ditentukan"
                    ],
                    [
                        "judul" => "Ketaatan terhadap instruksi kerja atasan",
                        "urut" => 4,
                        "pertanyaan" => "Melaksanakan tugas-tugas yang diberikan oleh atasan dengan baik, tepat dan cepat dan sesuai dengan instruksi atasan dan sesuai dengan Standart Operational Prosedure"
                    ],
                ]
            ],
        ];

        // $this->command->getOutput()->progressStart(count($data));
        $this->generate($data);
        // $this->command->getOutput()->progressFinish();
    }

    public function generate($data)
    {
        \DB::beginTransaction();
        try {
            foreach ($data as $key => $item) {
                $root = Tad::firstOrCreate(
                    [
                        'urut' => $key,
                        'judul' =>  $item['judul'],
                    ]
                );
                $root->child()
                    ->saveMany(
                        array_map(
                            function ($map) {
                                return new Tad($map);
                            },
                            $item['childs']
                        )
                    );
                // $this->command->getOutput()->progressAdvance();
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return $e->getMessage();
        }
    }
}
