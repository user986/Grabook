<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Request;

//use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends Request {

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
            'email' => 'required|email|max:255|unique:ApplicationUser'
        ];
    }
}
