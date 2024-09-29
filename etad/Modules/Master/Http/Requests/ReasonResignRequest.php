<?php

namespace Modules\Master\Http\Requests;

use App\Http\Requests\FormRequest;


class ReasonResignRequest extends FormRequest
{
    public function rules()
    {
        $id = $this->record->id ?? 0;
        return [
            'alasan'     => 'required',
        ];
    }

    public function customAttributes()
    {
        return [
            'alasan' => 'Alasan Resign',
        ];
    }
}
