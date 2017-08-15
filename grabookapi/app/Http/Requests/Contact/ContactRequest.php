<?php

namespace App\Http\Requests\Contact;

use App\Http\Requests\Request;

//use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends Request {

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
            'email' => 'email|max:255'
        ];
    }
}
