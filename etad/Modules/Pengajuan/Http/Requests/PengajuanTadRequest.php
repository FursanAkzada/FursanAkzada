<?php

namespace Modules\Pengajuan\Http\Requests;

use App\Http\Requests\FormRequest;

class PengajuanTadRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'vendor_id'         => ['required'],
            'so_id'             => ['required'],
            // 'uploads_so'        => ['required'],
            // 'uploads_sp.*'      => ['required'],
            // 'uploads_so.*'      => ['required'],
            // 'no'                => ['required'],
            'to'                => ['required'],
            'to.*'              => ['required'],
            'perihal'           => ['required'],
            'cc.*'              => ['required'],
            // 'uploads_so[uploaded]'        => ['required'],
            'pembukaan'         => ['required'],
            'penutupan'         => ['required'],
            'tgl_pengajuan'     => ['required'],
            'year'              => ['required'],
            'semester'          => ['required'],
            'kategori_id.*'     => ['required', 'exists:ref_kategori_vendor,id'],
            'jabatan_id.*'      => ['required', 'exists:ref_ehc_unit_kerja,idunit'],
            'jenis_kelamin.*'   => ['required', 'in:LP,L,P'],
            'jumlah.*'          => ['required', 'numeric', 'gt:0'],
            'vendor_id.*'       => ['required', 'exists:ref_vendor,id'],
        ];

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'so_id'             => 'Unit Kerja',
            'no'                => 'No. Surat',
            'to.'               => 'Kepada',
            'perihal'           => 'perihal',
            // 'uploads_so'        => 'File Struktur Organisasi',
            // 'uploads_sp'        => 'File Surat Permohonan',
            'tgl_pengajuan'     => 'Tgl Pengajuan',
            'year'              => 'Tahun',
            'semester'          => 'Semester',
            'kategori_id.*'     => 'Kategori',
            'jabatan_id.*'      => 'Jabatan',
            'jenis_kelamin.*'   => 'Jenis Kelamin',
            'jumlah.*'          => 'Jumlah',
            'vendor_id.*'       => 'Vendor',
        ];

        return $attributes;
    }
}

