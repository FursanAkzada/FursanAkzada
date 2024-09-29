<?php

namespace Modules\Master\Http\Requests;

use App\Http\Requests\FormRequest;

class RiskAssessmentRequest extends FormRequest
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
                'category'              => 'required',
                'year'                  => 'required',
                'org_struct_id'         => 'required',
                'key'                   => 'required|unique:ref_risk_assessment,key,' . $this->id . ',id,category,' . $this->category . ',org_struct_id,' . $this->org_struct_id . ',year,' . $this->year,
                'detail.*.risk_rating_id'   => 'required',
            ];
        } elseif ($this->category == 2) {
            $rules = [
                'category'              => 'required',
                'year'                  => 'required',
                'ict_object_id'         => 'required',
                'key'                   => 'required|unique:ref_risk_assessment,key,' . $this->id . ',id,category,' . $this->category . ',ict_object_id,' . $this->ict_object_id . ',year,' . $this->year,
                'detail.*.risk_rating_id'   => 'required',
            ];
        } else {
            $rules = [
                'category'              => 'required',
                'year'                  => 'required',
                'org_struct_id'         => 'required',
                'key'                   => 'required|unique:ref_risk_assessment,key,' . $this->id . ',id,category,' . $this->category . ',org_struct_id,' . $this->org_struct_id . ',year,' . $this->year,
                'detail.*.risk_rating_id'   => 'required',
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
            'key'                => 'Key Activities',
            'year'               => 'Tahun',
            'detail.*.risk_rating_id' => 'Risk Rating',
        ];
    }
}
