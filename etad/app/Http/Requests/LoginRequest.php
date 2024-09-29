<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseRequest;

class LoginRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'email'     => 'required',
            'password'  => 'required',
            'captcha'   => 'required|captcha',
        ];
    }

    public function attributes()
    {
        return [
            'captcha' => 'Kode Captcha'
        ];
    }
}
