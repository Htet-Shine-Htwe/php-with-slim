<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Contracts\SessionInterface;

class StartSessionMiddleware implements MiddlewareInterface
{

    public function __construct(private readonly SessionInterface $session)
    {

    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) :ResponseInterface
    {
        $this->session->start();

        $response = $handler->handle($request);

        $this->session->close();
        session_write_close();
        return $response;
    }
}
