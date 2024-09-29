<?php

namespace Modules\Master\Http\Requests\Tad;

use Illuminate\Foundation\Http\FormRequest;

class KeluargaUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tipe_id'         => ['required','exists:ref_tipe_keluarga,id'],
            'nama'            => ['required'],
            'urutan_anak'     => ['required_if:parents.*.tipe_id,10'],
            'tempat_lahir'    => ['required'],
            'tanggal_lahir'   => ['required', 'date_format:d/m/Y'],
            'alamat'          => ['required'],
            'jenis_kelamin'   => ['required','in:LP,L,P'],
            'agama_id'        => ['required'],
            'telepon'         => '',
            'kewarganegaraan' => ['required']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function customAttributes()
    {
        return [
            'tipe_id' => 'Keluarga Sebagai',
            'name' => 'Nama',
            'urutan_anak'     => 'Urutan Anak',
            // 'tempat_lahir'    => 'Tempat Lahir',
            // 'tanggal_lahir'   => 'Tanggal Lahir',
            'alamat'          => 'Alamat Lengkap',
            'jenis_kelamin'   => 'Jenis Kelamin',
            'agama_id'        => 'Agama',
            'telepon'         => 'Nomor HP',
            'kewarganegaraan' => 'Kewarganegaraan'
        ];
    }

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
