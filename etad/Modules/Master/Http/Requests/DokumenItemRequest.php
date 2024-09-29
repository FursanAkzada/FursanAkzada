<?php

namespace Modules\Master\Http\Requests;

use App\Http\Requests\FormRequest;

class DokumenItemRequest extends FormRequest
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
                'org_struct_id'      => 'required',
                'item'               => 'required|unique:ref_document_item,item,' . $this->id . ',id,category,' . $this->category . ',org_struct_id,' . $this->org_struct_id . ',document_type_id,' . $this->document_type_id,
            ];
        } elseif ($this->category == 2) {
            $rules = [
                'category'           => 'required',
                'ict_object_id'      => 'required',
                'item'               => 'required|unique:ref_document_item,item,' . $this->id . ',id,category,' . $this->category . ',ict_object_id,' . $this->ict_object_id . ',document_type_id,' . $this->document_type_id,
            ];
        } else {
            $rules = [
                'category'           => 'required',
                'org_struct_id'      => 'required',
                'item'               => 'required|unique:ref_document_item,item,' . $this->id . ',id,category,' . $this->category . ',org_struct_id,' . $this->org_struct_id . ',document_type_id,' . $this->document_type_id,
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
            'item'               => 'Item',
        ];
    }
}
