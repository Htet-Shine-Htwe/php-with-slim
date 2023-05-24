<?php

namespace App\Controllers;

use App\Auth;
use App\Entity\User;
use App\Exceptions\ValidationException;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use Psr\Http\Message\ServerRequestInterface as Request;
use Valitron\Validator;

class AuthController
{
    public function __construct(private readonly Twig $twig, private readonly EntityManager $entityManager,private readonly Auth $auth)
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
        $data = $request->getParsedBody();
        $v = new Validator($data);
        $v->rule('required', ['email','password']);
        $v->rule('email', 'email');

        if(!$this->auth->attempt($data))
        {
            throw new ValidationException(['password' => 'You have entered invalid email or password']);
        };

        return $response->withHeader('Location','/')->withStatus(302);
    }

    public function register(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
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
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $response;
    }

    public function logOut(Request $request,Response $response):Response
    {
        $this->auth->logOut();
        return $response->withHeader('Location','/')->withStatus(302);
    }
}