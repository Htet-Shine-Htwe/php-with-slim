<?php

namespace App\Controllers;

use App\Auth;
use App\Contracts\RequestValidatorFactoryInterface;
use App\DataObjects\RegisterUserData;
use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Requests\RegisterUserRequestValidator;
use App\Requests\UserLoginRequestValidator;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;

class AuthController
{
    public function __construct(private readonly Twig $twig, 
    private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
    private readonly Auth $auth)
    {

    }

    public function loginView(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'auth/login.twig');
    }

    public function registerView(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'auth/register.twig');
    }

    public function login(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(UserLoginRequestValidator::class)->validate($request->getParsedBody());

        if(!$this->auth->attempt($data))
        {
            throw new ValidationException(['password' => 'You have entered invalid email or password']);
        };

        return $response->withHeader('Location','/')->withStatus(302);
    }

    public function register(Request $request, Response $response): Response
    {
        $data = $this->requestValidatorFactory->make(RegisterUserRequestValidator::class)->validate($request->getParsedBody());

        $this->auth->register(new RegisterUserData($data['name'],$data['email'],$data['password']));

        return $response->withHeader('Location','/')->withStatus(302);

    }

    public function logOut(Request $request,Response $response):Response
    {
        $this->auth->logOut();
        return $response->withHeader('Location','/')->withStatus(302);
    }
}