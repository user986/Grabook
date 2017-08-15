<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Request;

class ResetPasswordRequest extends Request {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function authorize()
    {
        return true;
    }
    public function rules() {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'min:5|max:20',
            'password_confirmation' => 'same:password',
        ];
    }

}
