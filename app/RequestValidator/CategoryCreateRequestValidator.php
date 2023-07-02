<?php

namespace App\RequestValidator;

use App\Contracts\RequestValidatorInterface;
use App\Exceptions\ValidationException;
use Doctrine\ORM\EntityManager;
use Valitron\Validator;

class CategoryCreateRequestValidator implements RequestValidatorInterface
{
    public function __construct(protected readonly EntityManager $entityManager)
    {

    }

    public function validate(array $data) :array
    {
        $v = new Validator($data);
        $v->rule('required','name');
        $v->rule('lengthMax','name',50);
        if (!$v->validate())
        {
            throw new ValidationException(errors :$v->errors());

        }
        return $data;
    }
}
