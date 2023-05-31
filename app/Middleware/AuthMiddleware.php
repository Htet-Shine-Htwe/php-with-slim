<?php

namespace App\Middleware;

use App\Contracts\AuthInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(protected ResponseFactory $responseFactory,protected readonly AuthInterface $auth)
    {

    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) :ResponseInterface
    {   
        if($user = $this->auth->user())
        {
            return $handler->handle($request->withAttribute('user',$user));
        }
        return $this->responseFactory->createResponse(302)->withHeader('Location','/login');
    }
}
