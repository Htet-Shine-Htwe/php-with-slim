<?php

namespace App\Middleware;

use App\Contracts\AuthInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;

class AuthenticateMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly AuthInterface $auth)
    {

    }
    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler):ResponseInterface
    {
        
        return $handler->handle($request->withAttribute('user',$this->auth->user()));
    }

}   
