<?php

namespace Modules\Pengajuan\Http\Requests;

use App\Http\Requests\FormRequest;

class PengajuanTadUpdateRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            // 'so_id'             => ['required'],
            'uploads_so'        => ['required'],
            'uploads_sp'        => ['required'],
            // 'no'                => ['required'],
            'to'                => ['required'],
            'to.*'              => ['required'],
            'perihal'           => ['required'],
            'tgl_pengajuan'     => ['required'],
            'pembukaan'              => ['required'],
            'penutupan'          => ['required'],
            'kategori_id.*'     => ['required', 'exists:ref_kategori_vendor,id'],
            'requirement.*.jabatan_id' => ['required', 'exists:ref_ehc_unit_kerja,idunit'],
            'requirement.*.jenis_kelamin' => ['required', 'in:LP,L,P'],
            'requirement.*.jumlah' => ['required', 'numeric', 'gt:0'],
            'requirement.*.vendor_id' => ['required', 'exists:ref_vendor,id'],
        ];

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'so_id'                    => 'Unit Kerja',
            'no'                => 'No. Surat',
            'to.'               => 'Kepada',
            'perihal'           => 'perihal',
            'uploads_so'               => 'File Struktur Organisasi',
            'uploads_sp'               => 'File Surat Permohonan',
            'tgl_pengajuan'            => 'Tgl Pengajuan',
            'year'                     => 'Tahun',
            'semester'                 => 'Semester',
            'pembukaan'                => 'Kalimat Pembukaan',
            'penutupan'                => 'Kalimat Penutupan',
            'kategori_id.*'            => 'Kategori',
            'requirement.*.jabatan_id'             => 'Jabatan',
            'requirement.*.jenis_kelamin'          => 'Jenis Kelamin',
            'requirement.*.jumlah'                 => 'Jumlah',
            'requirement.*.vendor_id'              => 'Vendor',
        ];

        return $attributes;
    }
}
