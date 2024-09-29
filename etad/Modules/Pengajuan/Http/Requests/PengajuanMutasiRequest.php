<?php

namespace Modules\Pengajuan\Http\Requests;

use App\Http\Requests\FormRequest;

class PengajuanMutasiRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'unit_kerja_asal'   => ['required'],
            'unit_kerja_tujuan' => ['required', 'different:unit_kerja_asal'],
            // 'uploads.*'                   => ['required'],
            // 'no'                => ['required'],
            'tgl_pengajuan'     => ['required'],
            'menunjuk'          => ['required'],
            'menindaklanjuti'   => ['required'],
            // 'user_id'                   => ['required'],
            // 'alasan_mutasi'     => ['required'],
            // 'tgl_mutasi'                => ['required'],
        ];

        if (!$this->is_update) {
            $rules += [
                'pegawai'                   => 'required|array',
                'pegawai.*.jabatan_id'      => 'required',
                'pegawai.*.id'              => 'required',
                'pegawai.*.tanggal_mutasi'  => [
                    'required',
                    'date_format:d/m/Y',
                    // 'date',
                    'before_or_equal:pegawai.*.tanggal_efektif',
                ],
                'pegawai.*.id'  => [
                    'required',
                ],
                'pegawai.*.jabatan_id'  => [
                    'required',
                ],
                'pegawai.*.tanggal_efektif' => [
                    'required',
                    'date_format:d/m/Y',
                    // 'date',
                    'after_or_equal:pegawai.*.tanggal_mutasi'
                ],
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'pegawai.*.jabatan_id'      => 'Posisi TAD',
            'pegawai.*.id'              => 'Personil',
            'pegawai.*.tanggal_mutasi'  => 'Tgl SK Mutasi',
            'pegawai.*.tanggal_efektif' => 'Tgl Efektif',
            'unit_kerja_asal'           => 'Unit Kerja Asal',
            'unit_kerja_tujuan'         => 'Unit Kerja Tujuan',
        ];

        return $attributes;
    }

    public function messages()
    {
        return [
            'required_if' => 'Harus Diisi',
        ];
    }
}
