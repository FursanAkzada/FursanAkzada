<?php

namespace Modules\Pengajuan\Http\Requests;

use App\Http\Requests\FormRequest;

class PengajuanRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        $rules = [
            'pegawai' => 'required|array',
            'pegawai.*.jabatan_id' => 'required',
            'pegawai.*.id' => 'required',
            'pegawai.*.tanggal_resign'  => 'required',
            'pegawai.*.tanggal_efektif' => [
                'required',
                'date_format:d/m/Y',
                // 'date',
                'after_or_equal:pegawai.*.tanggal_resign'
            ],
            'pegawai.*.alasan' => 'required',
            // 'no'            => 'required',
            'to'            => 'required',
            'to.*'          => 'required',
            'tgl_pengajuan' => 'required',
            // 'uploads.*'     => 'required_without:uploads.files_ids',
            'unit_kerja_id' => 'required',
            // 'user_id' => 'required'

        ];

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'surat'                    => 'File',
            'pegawai'                  => 'Pegawai',
            'pegawai.*.jabatan_id'     => 'Posisi',
            'pegawai.*.id'             => 'Pegawai',
            'pegawai.*.tanggal_resign' => 'Tanggal Resign',
            'pegawai.*.alasan' => 'Alasan',
        ];

        return $attributes;
    }
}
