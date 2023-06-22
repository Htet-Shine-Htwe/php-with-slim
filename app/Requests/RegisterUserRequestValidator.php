<?php

namespace App\Requests;

use App\Contracts\RequestValidatorInterface;
use App\Exceptions\ValidationException;
use Doctrine\ORM\EntityManager;
use Valitron\Validator;

class RegisterUserRequestValidator implements RequestValidatorInterface
{

    public function __construct(protected readonly EntityManager $entityManager)
    {

    }

    public function validate(array $data) :array
    {
        $v = new Validator($data);
        $v->rule('required', ['name', 'email','password','confirmPassword']);
        $v->rule('email', 'email');
        $v->rule('equals', 'confirmPassword','password')->label('Confirm Password');
        $v->rule(
            fn($field,$value,$params,$fields)=> !$this->entityManager->getRepository(User::class)->count(
                ['email' => $value]
            ),'email'
        )->message('User with this email is already exists');
        if ($v->validate())
        {
            echo "Yay! We're all good!";
        }
        else
        {
            throw new ValidationException(errors :$v->errors());
        }


        return $data;
    }
}
