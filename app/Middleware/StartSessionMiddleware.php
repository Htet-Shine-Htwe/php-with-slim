<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StartSessionMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) :ResponseInterface
    {
        if(session_status() === PHP_SESSION_ACTIVE)
        {
            throw new \RuntimeException('Session was already been started');
        }
        
        if(headers_sent($filename,$line))
        {
            throw new \RuntimeException('Header already sent');
        }
        session_start();

        $response = $handler->handle($request);
        session_write_close();
        return $response;
    }
}
