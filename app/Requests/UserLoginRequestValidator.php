<?php

namespace App\Requests;
use App\Contracts\RequestValidatorInterface;
use App\Exceptions\ValidationException;
use Valitron\Validator;


class UserLoginRequestValidator implements RequestValidatorInterface
{

    public function validate(array $data) :array
    {
        $v = new Validator($data);
        $v->rule('required', ['email','password']);
        $v->rule('email', 'email');
        
        if(!$v->validate())
        {
            throw new ValidationException(errors :$v->errors());
        }

        return $data;
    }
}
