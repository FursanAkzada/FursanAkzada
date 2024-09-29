<?php

namespace Modules\Master\Http\Requests;

// use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Requests\FormRequest;

class PenyediaJasaRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name'          => 'required|max:255|unique:ref_org_structs,name,' . $this->id . ',id,level,penyedia-jasa',
            'address'       => 'required',
            'province_id'      => 'required',
            'city_id'          => 'required',
            'phone'         => 'required',
            'email'         => 'required',
            'pic_id'        => 'required',
        ];
        return $rules;
    }

    public function customAttributes()
    {
        return [
            'name' => 'Nama Perusahaan',
            'address' => 'Alamat Perusahaan',
            'province_id' => 'Provinsi',
            'city_id' => 'Kota / Kabupaten',
            'phone' => 'Telepon',
            'pic_id' => 'Penanggung Jawab',
        ];
    }
}
