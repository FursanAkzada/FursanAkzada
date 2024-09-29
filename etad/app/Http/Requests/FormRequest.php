<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseRequest;

class FormRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() || !is_null($this->user());
    }

    public function rules()
    {
        return [];
    }

    public function attributes()
    {
        $attributes = [];
        $rules = $this->rules();
        foreach ($rules as $name => $rule) {
            $attribute = ucfirst($name);
            $attributes += [
                $name => str_replace('_', ' ', $attribute)
            ];
        }

        $attributes = array_merge($attributes, $this->customAttributes());
        return $attributes;
    }

    public function customAttributes()
    {
        return [];
    }
}
