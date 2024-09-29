<?php

namespace Modules\Master\Http\Requests\Tad;

use Illuminate\Foundation\Http\FormRequest;

class KeluargaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'parents.*.tipe_id'         => ['required','exists:ref_tipe_keluarga,id'],
                    'parents.*.nama'            => ['required'],
                    'parents.*.urutan_anak'     => ['required_if:parents.*.tipe_id,10'],
                    'parents.*.tempat_lahir'    => ['required'],
                    'parents.*.tanggal_lahir'   => ['required', 'date_format:d/m/Y'],
                    'parents.*.alamat'          => ['required'],
                    'parents.*.jenis_kelamin'   => ['required','in:LP,L,P'],
                    'parents.*.agama_id'        => ['required','exists:ref_ehc_agama,Sandi'],
                    'parents.*.telepon'         => ['required','numeric'],
                    'parents.*.kewarganegaraan' => ['required']
                ];
                break;

            case 'PUT':
                return [
                    'parents.*.tipe_id'         => ['required','exists:ref_tipe_keluarga,id'],
                    'parents.*.nama'            => ['required'],
                    'parents.*.urutan_anak'     => ['required_if:parents.*.tipe_id,10'],
                    'parents.*.tempat_lahir'    => ['required'],
                    'parents.*.tanggal_lahir'   => ['required', 'date_format:d/m/Y'],
                    'parents.*.alamat'          => ['required'],
                    'parents.*.jenis_kelamin'   => ['required','in:LP,L,P'],
                    'parents.*.agama_id'        => ['required','exists:ref_ehc_agama,Sandi'],
                    'parents.*.telepon'         => ['required','numeric'],
                    'parents.*.kewarganegaraan' => ['required'],
                ];
                break;

            default:
                # code...
                break;
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'required_if'=> 'Harus Diisi',
        ];
    }
}
