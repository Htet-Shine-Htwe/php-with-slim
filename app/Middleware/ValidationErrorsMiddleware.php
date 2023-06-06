<?php

namespace App\Middleware;

use App\Contracts\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Views\Twig;

class ValidationErrorsMiddleware implements MiddlewareInterface
{
    public function __construct(protected readonly Twig $twig,private readonly SessionInterface $session)
    {

    }

    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler) :ResponseInterface
    {

        if($errors = $this->session->getFlash('errors'))
        {
            $this->twig->getEnvironment()->addGlobal('errors',$errors);
        }

        $response = $handler->handle($request);
        return $response;
    }
}
