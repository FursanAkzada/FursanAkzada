<?php

namespace Database\Seeders;

use App\Entities\EHC\Jabatan;
use Illuminate\Database\Seeder;
use Modules\Master\Entities\KategoriVendor;
use Modules\Master\Entities\Vendor;
use Modules\Master\Entities\VendorKategoriPivot;

class KategoriVendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $VENDORS = [
            [
                'nama' => 'PT. KOPABA JATIM',
            ],
            [
                'nama'  => 'PT. PERSONA PRIMA UTAMA'
            ],
            [
                'nama'  => 'PT. DUTA GRIYA SARANA'
            ],
            [
                'nama'  => 'PT. BIJAK'
            ],
        ];
        foreach ($VENDORS as $val) {
            $record = Vendor::where(
                'nama',
                $val['nama']
            )->first();
            if (!$record) {
                $record = new Vendor;
            }
            $record->nama = $val['nama'];
            $record->save();
        }
        $KATEGORI_VENDOR = [
            [
                'nama'      => 'Teknologi Informasi',
                'POSISI_TAD' => [
                    [
                        'NM_UNIT'   => 'Database Administrator',
                        'jenis'     => 'J-902',
                    ],
                    [
                        'NM_UNIT'   => 'IT Support',
                        'jenis'     => 'J-902',
                    ],
                    [
                        'NM_UNIT'   => 'Software Developer',
                        'jenis'     => 'J-902',
                    ],
                    [
                        'NM_UNIT'   => 'IT Development',
                        'jenis'     => 'J-901',
                    ],
                    [
                        'NM_UNIT'   => 'Teknisi',
                        'jenis'     => 'J-902',
                    ],
                    [
                        'NM_UNIT'   => 'Operator',
                        'jenis'     => 'J-902',
                    ],
                ]
            ],
            [
                'nama'      => 'Fasilitas Umum',
                'POSISI_TAD' => [
                    [
                        'NM_UNIT'   => 'Cleaning Service',
                        'jenis'     => 'J-902',
                    ],
                    [
                        'NM_UNIT'   => 'Pengemudi',
                        'jenis'     => 'J-902',
                    ],
                ],
            ],
            [
                'nama'      => 'Administrasi',
                'POSISI_TAD' => [
                    [
                        'NM_UNIT'   => 'Administrasi',
                        'jenis'     => 'J-901',
                    ],
                    [
                        'NM_UNIT'   => 'Admin Kontrak',
                        'jenis'     => 'J-901',
                    ],
                    [
                        'NM_UNIT'   => 'Sekretaris',
                        'jenis'     => 'J-901',
                    ],
                    [
                        'NM_UNIT'   => 'Kasir',
                        'jenis'     => 'J-901',
                    ],
                    [
                        'NM_UNIT'   => 'KASIR SAMSAT',
                        'jenis'     => 'J-901',
                    ],
                ],
            ],
            [
                'nama'      => 'Keamanan',
                'POSISI_TAD' => [
                    [
                        'NM_UNIT'   => 'Keamanan',
                        'jenis'     => 'J-902',
                    ],
                    [
                        'NM_UNIT'   => 'Security',
                        'jenis'     => 'J-902',
                    ],
                ],
            ],
            [
                'nama'      => 'Security',
                'POSISI_TAD' => [
                    [
                        'NM_UNIT'   => 'Keamanan',
                        'jenis'     => 'J-902',
                    ],
                    [
                        'NM_UNIT'   => 'Security',
                        'jenis'     => 'J-902',
                    ],
                ],
            ],
            [
                'nama'      => 'Pramubakti',
                'POSISI_TAD' => [
                    [
                        'NM_UNIT'   => 'Pramubakti',
                        'jenis'     => 'J-902',
                    ],
                    [
                        'NM_UNIT'   => 'OUTSOURCING LAINNYA',
                        'jenis'     => 'J-901',
                    ],
                    [
                        'NM_UNIT'   => 'OUTSOURCING PRAMUBAKTI',
                        'jenis'     => 'J-902',
                    ],
                    [
                        'NM_UNIT'   => 'Marketing',
                        'jenis'     => 'J-902',
                    ],
                    [
                        'NM_UNIT'   => 'Pemasaran',
                        'jenis'     => 'J-901',
                    ],
                ],
            ],
            [
                'nama'      => 'Cleaning',
                'POSISI_TAD' => [
                    [
                        'NM_UNIT'   => 'Cleaning',
                        'jenis'     => 'J-902',
                    ],
                ],
            ],
        ];
        foreach ($KATEGORI_VENDOR as $key => $kategori_vendor) {
            $kategoriVendor = KategoriVendor::where(
                'nama',
                $kategori_vendor['nama']
            )->first();
            if (!$kategoriVendor) {
                $kategoriVendor = new KategoriVendor;
            }
            $kategoriVendor->nama = $kategori_vendor['nama'];
            $kategoriVendor->save();
            foreach ($kategori_vendor['POSISI_TAD'] as $key => $posisi_tad) {
                $jabatan = Jabatan::where(
                    'NM_UNIT',
                    $posisi_tad['NM_UNIT']
                )->first();
                if (!$jabatan) {
                    $jabatan = new Jabatan;
                    $jabatan->idunit = Jabatan::count() + 1;
                }
                $jabatan->fill($posisi_tad);
                $jabatan->kategori_id = $kategoriVendor->id;
                $jabatan->save();
            }
        }
    }
}
