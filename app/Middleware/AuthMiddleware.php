<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(protected ResponseFactory $responseFactory)
    {

    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) :ResponseInterface
    {   
        if(empty($_SESSION['user']))
        {
            return $this->responseFactory->createResponse(302)->withHeader('Location','/login');
        }
        return $handler->handle($request);
    }
}
