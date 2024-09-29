<?php

namespace Modules\Master\Http\Requests\Tad;

use Illuminate\Foundation\Http\FormRequest;

class TadRequest extends FormRequest
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
                    'vendor_id'         => ['required'],
                    'nama'              => ['required'],
                    'email'             => ['required', 'unique:ref_tad,email'],
                    'telepon'           => ['required', 'numeric'],
                    'nik'               => ['required', 'numeric', 'unique:ref_tad,nik', 'digits:16'],
                    // 'npwp'              => ['required', 'unique:ref_tad,npwp', 'numeric', 'digits:15'],
                    // 'bpjs'              => ['required', 'unique:ref_tad,bpjs', 'numeric', 'digits:13'],
                    // 'rekening_bjtm'     => ['required', 'digits:10'],
                    'jenis_kelamin'     => ['required', 'in:LP,L,P'],
                    'status_perkawinan' => ['required', 'in:Lajang,Menikah,Cerah'],
                    'agama_id'          => ['required', 'exists:ref_ehc_agama,Sandi'],
                    'tempat_lahir'      => ['required'],
                    'tanggal_lahir'     => ['required'],
                    'alamat_lengkap'    => ['required'],
                    'city_id'           => ['required'],
                    'province_id'       => ['required'],
                    // 'kota_id'           => ['required', 'exists:ref_city,id'],
                    'pendidikan_id'     => ['required', 'exists:ref_ehc_sekolah,sandi'],
                    'jabatan_id'        => ['required'],
                    'uploads_foto3x4.*'              => ['required'],
                    'uploads_foto_fullbody.*'         => ['required'],
                    'uploads_cv.*'                => ['required'],
                    // 'jurusan_id'             => ['required'],
                ];

            case 'PUT':
                $id = request()->id;
                return [
                    'nama'              => ['required'],
                    'email'             => ['required', 'unique:ref_tad,email,' . $id],
                    'telepon'           => ['required', 'numeric'],
                    // 'nio'               => ['required_if:cek_kepegawaian,yes', 'unique:ref_tad_kepegawaian,nio,'. $id .',tad_id'],
                    'nik'               => ['required', 'numeric', 'unique:ref_tad,nik,' . $id, 'digits:16'],
                    // 'npwp'              => ['required', 'numeric', 'unique:ref_tad,npwp,' . $id, 'digits:15'],
                    // 'bpjs'              => ['required', 'numeric', 'unique:ref_tad,bpjs,' . $id], 'digits:13',
                    // 'rekening_bjtm'     => ['sometimes', 'digits:10'],
                    'jenis_kelamin'     => ['required', 'in:LP,L,P'],
                    'status_perkawinan' => ['required', 'in:Lajang,Menikah,Cerah'],
                    'agama_id'          => ['required', 'exists:ref_ehc_agama,Sandi'],
                    'tempat_lahir'      => ['required'],
                    'tanggal_lahir'     => ['required', 'date_format:d/m/Y'],
                    'alamat_lengkap'    => ['required'],
                    'province_id'       => ['required', 'exists:ref_province,id'],
                    'city_id'           => ['required', 'exists:ref_city,id'],
                    'pendidikan_id'     => ['required', 'exists:ref_ehc_sekolah,sandi'],
                    'uploads_foto3x4.*'              => ['required_without:uploads_foto3x4.files_ids'],
                    'uploads_foto_fullbody.*'         => ['required_without:uploads_foto_fullbody.files_ids'],
                    'uploads_cv.*'                => ['required_without:uploads_cv.files_ids'],
                    // 'jurusan_id'             => ['required'],
                    // 'gelar'             => ['required'],
                    'date_old_contract' => ['required_with:nio']
                ];

            default:
                return [];
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

    function messages()
    {
        return [
            'required_with' => 'tidak boleh kosong'
        ];
    }
}
