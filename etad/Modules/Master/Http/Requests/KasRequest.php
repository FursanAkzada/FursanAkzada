<?php

namespace Modules\Master\Http\Requests;

use App\Http\Requests\FormRequest;


class KasRequest extends FormRequest
{
    public function rules()
    {
        return [
            'parent_id'     => 'required',
            'code'          => 'required|max:255|unique:ref_org_structs,code,' . $this->id . ',id,level,kas',
            'name'          => 'required|max:255|unique:ref_org_structs,name,' . $this->id . ',id,level,kas',
            'address'       => 'required',
            'phone'         => 'required',
            // 'province_id'   => 'required',
            // 'city_id'       => 'required',
        ];
    }

    public function customAttributes()
    {
        return [
            'parent_id' => 'Parent',
            'name' => 'Nama',
            'address' => 'Alamat',
            'phone' => 'Telepon',
            // 'province_id' => 'Provinsi',
            // 'city_id' => 'Kota / Kabupaten',
        ];
    }
}
