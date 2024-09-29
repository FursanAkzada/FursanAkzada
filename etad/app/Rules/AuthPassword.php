<?php

namespace App\Rules;

use App\Entities\User;
use Illuminate\Contracts\Validation\Rule;

class AuthPassword implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $record = User::find(auth()->id());
        // if ($record->isEhc){
        //     /* Change EHC User */
        //     return (md5($value) == $record->password) ? true : false;
        // } else {
            /* Change Vendor User */
            return \Hash::check($value,$record->password);
        // }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Your password is wrong';
    }
}
