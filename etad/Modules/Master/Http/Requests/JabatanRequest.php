<?php

namespace Modules\Master\Http\Requests;

use App\Http\Requests\FormRequest;

class JabatanRequest extends FormRequest
{
    public function rules()
    {
        return [
            'org_struct_id'     => 'required',
            'name'          => 'required|max:255|unique:ref_positions,name,'. $this->id.',id,org_struct_id,' . $this->org_struct_id,
        ];
    }

    public function customAttributes()
    {
        return [
            'org_struct_id' => 'Lokasi',
            'name' => 'Nama',
        ];
    }
}
