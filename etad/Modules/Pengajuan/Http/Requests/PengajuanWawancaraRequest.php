<?php

namespace Modules\Pengajuan\Http\Requests;

use App\Http\Requests\FormRequest;

class PengajuanWawancaraRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'tgl_wawancara'              => ['required'],
            'pewawancaras'               => ['required'],
        ];

        return $rules;
    }

    public function attributes()
    {
        $attributes = [
            'tgl_wawancara'              => 'Tgl Wawancara',
            'pewawancaras'               => 'Pewawancara',
        ];

        return $attributes;
    }
}