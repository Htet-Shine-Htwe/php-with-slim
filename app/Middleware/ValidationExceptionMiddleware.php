<?php

namespace App\Middleware;

use App\Exceptions\ValidationException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationExceptionMiddleware implements MiddlewareInterface
{

    public function __construct(private ResponseFactoryInterface $responseFactory)
    {

    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try{
            return $handler->handle($request);
        }
        catch(ValidationException $e) 
        {
            $response = $this->responseFactory->createResponse();
            return $response->withHeader('Location','/register')->withStatus(302);
        }
    }
}