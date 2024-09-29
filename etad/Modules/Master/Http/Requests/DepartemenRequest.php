<?php

namespace Modules\Master\Http\Requests;

use App\Http\Requests\FormRequest;

class DepartemenRequest extends FormRequest
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
            'code'      => 'required|max:255|unique:ref_org_structs,code,' . $this->id . ',id,level,departemen',
            'name'      => 'required|max:255|unique:ref_org_structs,name,' . $this->id . ',id,level,departemen',
        ];
    }

    public function customAttributes()
    {
        return [
            'parent_id' => 'Parent',
            'name' => 'Nama',
        ];
    }
}
