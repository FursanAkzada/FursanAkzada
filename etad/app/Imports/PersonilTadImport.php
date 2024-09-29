<?php

namespace App\Imports;

use App\Entities\EHC\Agama;
use App\Entities\EHC\Jabatan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Cell;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Modules\Master\Entities\Geografis\City;
use Modules\Master\Entities\Geografis\Province;
use Modules\Master\Entities\Jurusan;
use Modules\Master\Entities\KategoriVendor;
use Modules\Master\Entities\Pendidikan;
use Modules\Master\Entities\SO\OrgStruct;
use Modules\Master\Entities\Tad\Kepegawaian;
use Modules\Master\Entities\Tad\KepegawaianFailed;
use Modules\Master\Entities\Tad\Tad;
use Modules\Master\Entities\Tad\TadFailed;
use Modules\Master\Entities\Vendor;
use Modules\Pengajuan\Entities\Tad\Quota;
use Modules\Pengajuan\Entities\Tad\QuotaPeriode;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PersonilTadImport implements ToCollection, WithStartRow
{
    use Importable;
    const HEADER_SPEC = [
        "Kategori TAD\n(bisa lebih dari satu, dipisahkan koma)",
        "Vendor",
        "Nama Lengkap",
        "NIK",
        "NPWP",
        "Nomor BPJS",
        "Rekening Bank Jatim",
        "Agama\n(Islam/Katholik/Protestan/Hindu/Budha)",
        "Jenis Kelamin\n(L/P)",
        "Tempat Lahir",
        "Tgl Lahir\n(dd/mm/yyyy cont: 17/08/1945)",
        "Status Perkawinan\n(Lajang/Menikah/Cerai)",
        "Nomor Handphone",
        "Email",
        "Alamat Lengkap",
        "Kota/Kabupaten\n(Lihat data Master Kota/Kabupaten)",
        "Pendidikan",
        "Jurusan",
        "Gelar",
        "Rekomendasi",
        "Kategori TAD untuk Jabatan/Posisi TAD",
        "Jabatan/Posisi TAD",
        "Parent Unit Kerja",
        "Level Unit Kerja\n(Direksi/SEVP/Divisi/Sub Divisi/Cabang/Cabang Pembantu/Kantor Kas)",
        "Unit Kerja",
        "Tahun Quota TAD\n(yyyy cont: 2020)",
        "Semester Quota TAD\n(Satu/Dua)",
        "Tgl Mulai Kontrak Kerja\n(dd/mm/yyyy cont: 17/08/1945)",
        "Tgl Selesai Kontrak Kerja\n(dd/mm/yyyy cont: 17/08/1945)",
        "NIO",
    ];
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        // dd(67, $collection);
        // return 68;
        // return $collection;
    }

    function insert(Collection $collection)
    {
        // Validasi Template
        $row = $collection->first()->first();
        for ($i = 0; $i < count(Self::HEADER_SPEC); $i++) {
            $excel_cell = $row[$i];
            $spec_cell = Self::HEADER_SPEC[$i];
            if ($excel_cell !== $spec_cell) {
                throw new \Exception("File tidak tidak sesuai dengan template terbaru. Silahkan download template kembali!", 1);
            }
        }

        // Maping Data
        $list_personil = [];
        $list_gagal = 0;
        foreach ($collection->first() as $row_index => $row) {
            if ($row_index == 0) continue;

            $r_vendor_categories    = trim($row[0] ?? '');
            $r_jabatan              = trim($row[21] ?? '');
            $r_vendor               = trim($row[1] ?? '');
            $r_nama_lengkap         = trim($row[2] ?? '');
            $r_nik                  = trim($row[3] ?? '');
            $r_npwp                 = trim($row[4] ?? '');
            $r_bpjs                 = trim($row[5] ?? '');
            $r_rekening             = trim($row[6] ?? '');
            $r_agama                = trim($row[7] ?? '');
            $r_jenis_kelamin        = trim($row[8] ?? '');
            $r_tempat_lahir         = trim($row[9] ?? '');
            $r_tgl_lahir            = trim($row[10] ?? '');
            $r_status_perkawinan    = trim($row[11] ?? '');
            $r_telepon              = trim($row[12] ?? '');
            $r_email                = trim($row[13] ?? '');
            $r_alamat_lengkap       = trim($row[14] ?? '');
            $r_kota_kab             = trim($row[15] ?? '');
            $r_pendidikan           = trim($row[16] ?? '');
            $r_jurusan              = trim($row[17] ?? '');
            $r_gelar                = trim($row[18] ?? '');
            $r_rekomendasi          = trim($row[19] ?? '');
            $r_kategori_jabatan     = trim($row[20] ?? '');
            $r_parent_unit_kerja    = trim($row[22] ?? '');
            $r_level_unit_kerja     = trim($row[23] ?? '');
            $r_unit_kerja           = trim($row[24] ?? '');
            $r_tahun_quota          = trim($row[25] ?? '');
            $r_semester_quota       = trim($row[26] ?? '');
            $r_tgl_kontrak_mulai    = trim($row[27] ?? '');
            $r_tgl_kontrak_selesai  = trim($row[28] ?? '');
            $r_nio                  = trim($row[29] ?? '');

            $vendor_category_ids = [];

            // kategori TAD
            if ($r_vendor_categories != '') {
                foreach (explode(',', $r_vendor_categories) as $key => $category) {
                    if ($category != '') {
                        $vendor_category_import_excel = KategoriVendor::firstOrNew(
                            [
                                'nama'  => $category,
                            ]
                        );
                        $vendor_category_import_excel->save();
                        $vendor_category_ids[] = $vendor_category_import_excel->id;
                    }
                }
                // vendor
                if ($r_vendor != '') {
                    $vendor = Vendor::firstOrNew(
                        [
                            'nama'  => $r_vendor,
                        ]
                    );
                    $vendor->save();
                    $vendor->categories()->syncWithoutDetaching($vendor_category_ids);
                }
            } else {
                $vendor = NULL;
            }

            // Agama
            if ($r_agama != '') {
                $agama = Agama::where('Lengkap', $r_agama)->first();
                if ($agama == NULL) {
                    $agama = Agama::where('Lengkap', 'N/A')->first();
                }
            } else {
                $agama = Agama::where('Lengkap', 'N/A')->first();
            }

            // Kota kabupaten
            if ($r_kota_kab != '') {
                $city = City::where('name', 'LIKE', '%' . $r_kota_kab . '%')->first();
                if ($city == NULL) {
                    $city = City::firstOrNew(
                        [
                            'name'  => $r_kota_kab,
                        ]
                    );
                    $city->province_id ??= 15;
                    $city->save();
                }
            } else {
                $city = NULL;
            }

            // Pendidikan
            $pendidikan = NULL;
            $jabatan = NULL;
            if ($r_pendidikan != '') {
                $pendidikan = Pendidikan::where('name', $r_pendidikan)->first();
                if ($r_jurusan != '' && $pendidikan != NULL) {
                    $jurusan = Jurusan::firstOrNew(
                        [
                            'name'  => $r_jurusan,
                        ]
                    );
                    $jurusan->pendidikan_id = $pendidikan->id ?? NULL;
                    $jurusan->save();
                }
            }

            // kategori jabatan
            $kategori_jabatan = NULL;
            $jabatan = NULL;
            if ($r_kategori_jabatan != '') {
                $kategori_jabatan = KategoriVendor::where('nama', $r_kategori_jabatan)->first();
                if ($kategori_jabatan == NULL) {
                    $kategori_jabatan = KategoriVendor::firstOrNew(
                        [
                            'nama'  => $r_kategori_jabatan,
                        ]
                    );
                    $kategori_jabatan->save();
                }
                if ($r_jabatan != '' && $kategori_jabatan != NULL) {
                    $jabatan = Jabatan::where('NM_UNIT', $r_jabatan)->first();
                    if ($jabatan == NULL) {
                        $jabatan = Jabatan::firstOrNew(
                            [
                                'NM_UNIT'  => $r_jabatan,
                            ]
                        );
                        $jabatan->kategori_id = $kategori_jabatan->id;
                        $jabatan->idunit = Jabatan::max('idunit') + 1;
                        $jabatan->save();
                    }
                }
            }

            $unit_kerja_parent_id = null;
            $level_unit_kerja = [
                'Direksi'           => 'bod',
                'SEVP'              => 'vice',
                'Divisi'            => 'division',
                'Sub Divisi'        => 'departemen',
                'Cabang'            => 'cabang',
                'Cabang Pembantu'   => 'capem',
                'Kantor Kas'        => 'kas',
            ][$r_level_unit_kerja] ?? '-';
            if ($level_unit_kerja === '-') {
                throw new \Exception("Baris " . $row_index . ": Level Unit Kerja tidak sesuai format", 1);
            }
            $parent_unit_kerja = OrgStruct::where('name', $r_parent_unit_kerja)->first();
            if ($parent_unit_kerja) {
                $unit_kerja_parent_id = $parent_unit_kerja->id;
            } else {
                $unit_kerja_parent_id = [
                    'bod'           => 2,
                    'vice'          => 2,
                    'division'      => 2,
                    'departemen'    => 25,
                    'cabang'        => 2,
                    'capem'         => 10,
                    'kas'           => 10,
                ][$level_unit_kerja] ?? null;
            }
            $unit_kerja = NULL;
            if ($level_unit_kerja != NULL && $r_unit_kerja != NULL && $unit_kerja_parent_id != NULL) {
                $unit_kerja = OrgStruct::where('name', $r_unit_kerja)->first();
                if ($unit_kerja == NULL) {
                    $unit_kerja = OrgStruct::firstOrNew(
                        [
                            'level'     => $level_unit_kerja,
                            'name'      => $r_unit_kerja,
                            'parent_id' => $unit_kerja_parent_id,
                        ]
                    );
                    $unit_kerja->save();
                }
            }

            $quota_periode = QuotaPeriode::where([['semester', $r_semester_quota], ['year', $r_tahun_quota]])->first();
            if (!$quota_periode && $unit_kerja && $jabatan && $r_tgl_kontrak_mulai && $r_tgl_kontrak_selesai && $r_tahun_quota && $r_semester_quota && $r_nio) {
                $quota_periode              = new QuotaPeriode;
                $quota_periode->version     = 0;
                $quota_periode->year        = $r_tahun_quota;
                $quota_periode->semester    = $r_semester_quota;
                $quota_periode->save();
            }
            // cek gagal
            $cek_personil_tad_exists = Tad::where('nik', $r_nik)->exists();

            if ($r_vendor_categories === '' || $r_jabatan === '' || $r_vendor === '' || $r_nik === '' || $cek_personil_tad_exists) {
                $personil_failed_tad = new TadFailed;
                $personil_failed_tad->nik                  = $r_nik;
                $personil_failed_tad->source               = TadFailed::SOURCE_EXCEL;
                $personil_failed_tad->nama                 = $r_nama_lengkap ?? NULL;
                $personil_failed_tad->email                = $r_email ?? NULL;
                $personil_failed_tad->telepon              = $r_telepon ?? NULL;
                $personil_failed_tad->npwp                 = $r_npwp ?? NULL;
                $personil_failed_tad->bpjs                 = $r_bpjs ?? NULL;
                $personil_failed_tad->rekening_bjtm        = $r_rekening ?? NULL;
                $personil_failed_tad->agama_id             = $agama->Sandi ?? NULL;
                $personil_failed_tad->jenis_kelamin        = $r_jenis_kelamin ?? NULL;
                $personil_failed_tad->status_perkawinan    = $r_status_perkawinan ?? NULL;
                $personil_failed_tad->tempat_lahir         = $r_tempat_lahir ?? NULL;
                if ($r_tgl_lahir) {
                    $personil_failed_tad->tanggal_lahir    = date_format(Date::excelToDateTimeObject((int)($r_tgl_lahir)), 'd/m/Y') ?? NULL;
                }
                $personil_failed_tad->alamat_lengkap       = $r_alamat_lengkap ?? NULL;
                $personil_failed_tad->city_id              = $city->id ?? NULL;
                $personil_failed_tad->pendidikan_id        = $pendidikan->id ?? NULL;
                $personil_failed_tad->jurusan_id           = $jurusan->id ?? NULL;
                $personil_failed_tad->gelar                = $r_gelar ?? NULL;
                $personil_failed_tad->vendor_id            = $vendor->id ?? NULL;
                $personil_failed_tad->jabatan_id           = $jabatan->idunit ?? NULL;
                $personil_failed_tad->rekomendasi          = $r_rekomendasi ?? NULL;
                $personil_failed_tad->is_active            = 1;
                $personil_failed_tad->save();
                if ($personil_failed_tad && $unit_kerja && $jabatan && $r_tgl_kontrak_mulai && $r_tgl_kontrak_selesai && $r_tahun_quota && $r_semester_quota && $r_nio) {
                    $kepegawaian                = new KepegawaianFailed;
                    $kepegawaian->is_imported   = 1;
                    $kepegawaian->status        = KepegawaianFailed::WORK;
                    $kepegawaian->tad_id        = $personil_failed_tad->id;
                    $kepegawaian->jabatan_id    = $jabatan->idunit;
                    $kepegawaian->year          = $r_tahun_quota;
                    $kepegawaian->semester      = $r_semester_quota;
                    $kepegawaian->cabang_id     = $unit_kerja->id;
                    if ($r_tgl_kontrak_mulai) {
                        $kepegawaian->in_at    = date_format(Date::excelToDateTimeObject((int)($r_tgl_kontrak_mulai)), 'd/m/Y');
                    }
                    if ($r_tgl_kontrak_selesai) {
                        $kepegawaian->contract_due    = date_format(Date::excelToDateTimeObject((int)($r_tgl_kontrak_selesai)), 'd/m/Y');
                    }
                    $kepegawaian->nio           = $r_nio;
                    if ($personil_failed_tad->kepegawaian_id) {
                        $prev_kepegawaian   = KepegawaianFailed::find($personil_failed_tad->kepegawaian_id);
                        $prev_kepegawaian->status        = $kepegawaian->status;
                        $prev_kepegawaian->out_at        = now()->format('d/m/Y');
                        $prev_kepegawaian->save();

                        $kepegawaian->previous_employment_id = $prev_kepegawaian->id;
                    }
                    $kepegawaian->save();

                    $personil_failed_tad->kepegawaian_id = $kepegawaian->id;
                    $personil_failed_tad->save();
                }
                $list_gagal++;
            } else {
                $personil_tad = Tad::firstOrNew(
                    [
                        'nik'   => $r_nik,
                    ]
                );
                $personil_tad->source               = Tad::SOURCE_EXCEL;
                $personil_tad->nama                 = $r_nama_lengkap ?? NULL;
                $personil_tad->email                = $r_email ?? NULL;
                $personil_tad->telepon              = $r_telepon ?? NULL;
                $personil_tad->npwp                 = $r_npwp ?? NULL;
                $personil_tad->bpjs                 = $r_bpjs ?? NULL;
                $personil_tad->rekening_bjtm        = $r_rekening ?? NULL;
                $personil_tad->agama_id             = $agama->Sandi ?? NULL;
                $personil_tad->jenis_kelamin        = $r_jenis_kelamin ?? NULL;
                $personil_tad->status_perkawinan    = $r_status_perkawinan ?? NULL;
                $personil_tad->tempat_lahir         = $r_tempat_lahir ?? NULL;
                if ($r_tgl_lahir) {
                    $personil_tad->tanggal_lahir    = date_format(Date::excelToDateTimeObject((int)($r_tgl_lahir)), 'd/m/Y') ?? NULL;
                }
                $personil_tad->alamat_lengkap       = $r_alamat_lengkap ?? NULL;
                $personil_tad->city_id              = $city->id ?? NULL;
                $personil_tad->pendidikan_id        = $pendidikan->id ?? NULL;
                $personil_tad->jurusan_id           = $jurusan->id ?? NULL;
                $personil_tad->gelar                = $r_gelar ?? NULL;
                $personil_tad->vendor_id            = $vendor->id ?? NULL;
                $personil_tad->jabatan_id           = $jabatan->idunit ?? NULL;
                $personil_tad->rekomendasi          = $r_rekomendasi ?? NULL;
                $personil_tad->is_active            = 1;
                $personil_tad->save();
                if ($personil_tad && Jabatan::where('idunit', $jabatan->idunit)->count() && $unit_kerja && $r_tahun_quota && $r_semester_quota && $r_tgl_kontrak_mulai && $r_tgl_kontrak_selesai && $r_nio) {
                    $quota = Quota::whereHas(
                        'periode',
                        function ($q) use ($r_tahun_quota, $r_semester_quota) {
                            $q
                                ->where('year', $r_tahun_quota)
                                ->where('semester', $r_semester_quota);
                        }
                    )
                        ->when(
                            $unit_kerja,
                            function ($q) use ($unit_kerja) {
                                $q->where('org_struct_id', $unit_kerja->id);
                            }
                        )
                        ->when(
                            $jabatan,
                            function ($q) use ($jabatan) {
                                $q->where('posisi_tad_id', $jabatan->idunit);
                            }
                        )
                        ->first();
                    if (!$quota) {
                        $quota = Quota::firstOrNew(
                            [
                                'pengajuan_tad_quota_periode_id'    => $quota_periode->id,
                                'org_struct_id'                     => $unit_kerja->id,
                                'posisi_tad_id'                     => $jabatan->idunit
                            ]
                        );
                        $quota->status = $quota_periode->status === 'completed' ? 'submit' : 'draft';
                        $quota->quota ??= 1;
                        $quota->save();
                    }
                    if ($quota->quota <= ($quota->used['total'] ?? 0)) {
                        $quota->quota = ($quota->used['total'] ?? 0) + 1;
                    }
                    $quota->save();
                    if ($unit_kerja && $jabatan && $r_tgl_kontrak_mulai && $r_tgl_kontrak_selesai && $r_tahun_quota && $r_semester_quota && $r_nio) {
                        $kepegawaian                = new Kepegawaian;
                        $kepegawaian->is_imported   = 1;
                        $kepegawaian->status        = Kepegawaian::WORK;
                        $kepegawaian->tad_id        = $personil_tad->id;
                        $kepegawaian->jabatan_id    = $jabatan->idunit;
                        $kepegawaian->year          = $r_tahun_quota;
                        $kepegawaian->semester      = $r_semester_quota;
                        $kepegawaian->cabang_id     = $unit_kerja->id;
                        if ($r_tgl_kontrak_mulai) {
                            $kepegawaian->in_at    = date_format(Date::excelToDateTimeObject((int)($r_tgl_kontrak_mulai)), 'd/m/Y');
                        }
                        if ($r_tgl_kontrak_selesai) {
                            $kepegawaian->contract_due    = date_format(Date::excelToDateTimeObject((int)($r_tgl_kontrak_selesai)), 'd/m/Y');
                        }
                        $kepegawaian->nio           = $r_nio;
                        if ($personil_tad->kepegawaian_id) {
                            $prev_kepegawaian   = Kepegawaian::find($personil_tad->kepegawaian_id);
                            $prev_kepegawaian->status        = $kepegawaian->status;
                            $prev_kepegawaian->out_at        = now()->format('d/m/Y');
                            $prev_kepegawaian->save();

                            $kepegawaian->previous_employment_id = $prev_kepegawaian->id;
                        }
                        $kepegawaian->save();

                        $personil_tad->kepegawaian_id = $kepegawaian->id;
                        $personil_tad->save();
                    }
                }
                $list_personil[] = $personil_tad->id;
            }
        }
        $result = count(array_unique($list_personil));

        return [$result, $collection->first()->count() - 1, $list_gagal];
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 1;
    }
}
