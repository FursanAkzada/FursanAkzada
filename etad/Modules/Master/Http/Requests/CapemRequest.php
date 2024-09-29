<?php

namespace Modules\Master\Http\Requests;

use App\Http\Requests\FormRequest;

class CapemRequest extends FormRequest
{
    public function rules()
    {
        return [
            'parent_id'     => 'required',
            'code'          => 'required|unique:ref_org_structs,code,' . $this->id . ',id,level,capem',
            'name'          => 'required|max:255|unique:ref_org_structs,name,' . $this->id . ',id,level,capem',
            'address'       => 'required',
            'phone'         => 'required',
        ];
    }

    public function customAttributes()
    {
        return [
            'parent_id' => 'Parent',
            'code' => 'Kode',
            'name' => 'Nama',
            'address' => 'Alamat',
            'phone' => 'Telepon',
        ];
    }
}
