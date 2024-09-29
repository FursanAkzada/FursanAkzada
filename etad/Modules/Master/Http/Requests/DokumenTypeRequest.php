<?php

namespace Modules\Master\Http\Requests;

use App\Http\Requests\FormRequest;

class DokumenTypeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        if ($this->category == 1) {
            $rules = [
                'category'           => 'required',
                'org_struct_id'           => 'required',
                'aspect'             => 'required|unique:ref_document_type,aspect,' . $this->id . ',id,category,' . $this->category . ',org_struct_id,' . $this->org_struct_id,
            ];
        } elseif ($this->category == 2) {
            $rules = [
                'category'           => 'required',
                'ict_object_id'      => 'required',
                'aspect'             => 'required|unique:ref_document_type,aspect,' . $this->id . ',id,category,' . $this->category . ',ict_object_id,' . $this->ict_object_id,
            ];
        } else {
            $rules = [
                'category'           => 'required',
                'org_struct_id'           => 'required',
                'aspect'             => 'required|unique:ref_document_type,aspect,' . $this->id . ',id,category,' . $this->category . ',org_struct_id,' . $this->org_struct_id,
            ];
        }

        return $rules;
    }

    public function customAttributes()
    {
        return [
            'category'           => 'Kategori',
            'org_struct_id'      => 'Objek Audit',
            'ict_object_id'      => 'ICT Objek',
            'aspect'             => 'Aspek',
            'year'               => 'Tahun',
        ];
    }
}
