<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

class OldFormDataMiddleware implements MiddlewareInterface
{
    public function __construct(protected readonly Twig $twig)
    {

    }
    
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        if(! empty($_SESSION['old']))
        {
            $this->twig->getEnvironment()->addGlobal('old',$_SESSION['old']);
        }

        unset($_SESSION['old']);

        $response = $handler->handle($request);
        return $response;
    }
}
