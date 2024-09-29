<?php

namespace Modules\Master\Http\Requests;

use App\Http\Requests\FormRequest;

class LastAuditRequest extends FormRequest
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
                'year'               => 'required',
                'org_struct_id'      => 'required',
                'branch'               => 'required|unique:ref_last_audit,branch,' . $this->id . ',id,category,' . $this->category . ',org_struct_id,' . $this->org_struct_id . ',year,' . $this->year,
            ];
        } elseif ($this->category == 2) {
            $rules = [
                'category'           => 'required',
                'year'               => 'required',
                'ict_object_id'      => 'required',
                'branch'               => 'required|unique:ref_last_audit,branch,' . $this->id . ',id,category,' . $this->category . ',ict_object_id,' . $this->ict_object_id . ',year,' . $this->year,
            ];
        } else {
            $rules = [
                'category'           => 'required',
                'year'               => 'required',
                'org_struct_id'      => 'required',
                'branch'               => 'required|unique:ref_last_audit,branch,' . $this->id . ',id,category,' . $this->category . ',org_struct_id,' . $this->org_struct_id . ',year,' . $this->year,
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
            'branch'             => 'Branch Management',
            'year'               => 'Tahun',
        ];
    }
}
