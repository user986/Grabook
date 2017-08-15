<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\ResourceException;
abstract class Request extends FormRequest
{
   public function failedValidation(Validator $validator){ 
      // throw new ResourceException($validator->getMessageBag());
   	 $message = $validator->errors()->all();
   	 $result = json_encode($message[0]);
   	throw new ResourceException($result);
    }

    protected function failedAuthorization()
    {
        throw new ResourceException($this->forbiddenResponse());
    }
}
