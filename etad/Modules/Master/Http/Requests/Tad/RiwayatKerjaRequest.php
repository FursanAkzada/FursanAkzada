<?php

namespace Modules\Master\Http\Requests\Tad;

use Illuminate\Foundation\Http\FormRequest;

class RiwayatKerjaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['required'],
            'employment_type' => ['required'],
            'company' => ['required'],
            'location_company' => ['required'],
            'system_working' => ['required'],
            'description' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'required_if'=> 'Harus Diisi',
        ];
    }
}
