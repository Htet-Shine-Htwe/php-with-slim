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

        //to make sure the previous url is GET method
        if($request->getMethod() === 'GET'){
            $this->session->put('previousUrl',(string) $request->getUri());//cast to string because it has a __toString method in UriInterface
        }

        $this->session->close();
        session_write_close();
        return $response;
    }
}
