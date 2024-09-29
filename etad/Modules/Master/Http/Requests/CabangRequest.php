<?php

namespace Modules\Master\Http\Requests;

use App\Http\Requests\FormRequest;


class CabangRequest extends FormRequest
{
    public function rules()
    {
        return [
            'parent_id'     => 'required',
            'code'          => 'required|unique:ref_org_structs,code,' . $this->id . ',id,level,cabang',
            'name'          => 'required|max:255|unique:ref_org_structs,name,' . $this->id . ',id,level,cabang',
            'address'       => 'required',
            'phone'         => 'required',
            'province_id'   => 'required',
            'city_id'       => 'required',
        ];
    }

    public function customAttributes()
    {
        return [
            'parent_id' => 'Parent',
            'code' => 'Kode',
            'name' => 'Nama',
            'address' => 'Alamat',
            'province_id' => 'Provinsi',
            'city_id' => 'Kota / Kabupaten',
            'phone' => 'Telepon',
        ];
    }
}
