<?php

namespace Modules\Master\Http\Requests;

use App\Http\Requests\FormRequest;

class ViceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'parent_id' => 'required',
            'name'      => 'required|max:255|unique:ref_org_structs,name,'.$this->id.',id,level,vice',
        ];
    }

    public function customAttributes()
    {
        return [
            'name' => 'Nama',
        ];
    }
}
