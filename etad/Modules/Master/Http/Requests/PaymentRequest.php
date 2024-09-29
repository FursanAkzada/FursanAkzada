<?php

namespace Modules\Master\Http\Requests;

use App\Http\Requests\FormRequest;


class PaymentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'parent_id'     => 'required',
            'name'          => 'required|max:255|unique:ref_org_structs,name,' . $this->id . ',id,level,payment',
            'address'       => 'required',
        ];
    }

    public function customAttributes()
    {
        return [
            'parent_id' => 'Parent',
            'name' => 'Nama',
            'address' => 'Alamat',
        ];
    }
}
