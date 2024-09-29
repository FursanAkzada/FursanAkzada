<?php

namespace Modules\Pengajuan\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Rules\ValidateNioRule;

class PenerimaanRequest extends FormRequest
{
    public function rules()
    {
        // dd(request()->all());
        $rules = [
            'keputusan'             => ['required'],
            'nio'                   => [
                'required_if:keputusan,Diterima',
                'unique:ref_tad_kepegawaian,nio,' . $this->penerimaan_id,
                'numeric',
                $this->keputusan === 'Diterima' ? new ValidateNioRule : null
            ],
            'no_sk'                 => [
                'required_if:keputusan,Diterima'
            ],
            'tgl_keputusan'         => [
                'required',
                'date_format:d/m/Y',
                'before_or_equal:tanggal_sekarang',
                'before_or_equal:start_date_contract',
            ],
            'start_date_contract'   => [
                'required_if:keputusan,Diterima',
                'date_format:d/m/Y',
                'after_or_equal:tgl_keputusan',
                'before:tgl_contractdue',
            ],
            'tgl_contractdue'       => [
                'required_if:keputusan,Diterima',
                'date_format:d/m/Y',
                'after:start_date_contract',
            ],
        ];

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'uploads'               => 'File',
            'keputusan'             => 'Hasil Keputusan',
            'tgl_keputusan'         => 'Tgl Keputusan',
            'start_date_contract'   => 'Tgl Mulai Kontrak',
            'tgl_contractdue'       => 'Tgl Selesai Kontrak',
        ];

        return $attributes;
    }

    public function messages()
    {
        return [
            'required_if' => 'tidak boleh kosong.',
            'before'            => 'Tanggal tidak valid',
            'before_or_equal'   => 'Tanggal tidak valid',
            'after'             => 'Tanggal tidak valid',
            'after_or_equal'    => 'Tanggal tidak valid',
        ];
    }
}
