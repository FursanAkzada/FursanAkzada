<?php

namespace Modules\Pengajuan\Http\Requests;

use App\Http\Requests\FormRequest;

class CcRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|unique:trans_pengajuan_tad_cc,user_id,'.$this->id.',id,pengajuan_id,'.$this->pengajuan_id,
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function customAttributes()
    {
        return [
            'user_id' => 'User',
        ];
    }
}
