<?php

namespace App\Middleware;

use App\Contracts\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;

class GuestMiddleware implements MiddlewareInterface
{
    public function __construct(protected ResponseFactory $responseFactory,
    protected readonly SessionInterface $session)
    {

    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) :ResponseInterface
    {   
        if($this->session->get('user'))
        {
            return $this->responseFactory->createResponse(302)->withHeader('Location','/');
        }
        return $handler->handle($request);
    }
}
