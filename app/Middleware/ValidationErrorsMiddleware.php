<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Views\Twig;

class ValidationErrorsMiddleware implements MiddlewareInterface
{
    public function __construct(protected readonly Twig $twig)
    {

    }

    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler) :ResponseInterface
    {

        if(! empty($_SESSION['errors']))
        {
            $this->twig->getEnvironment()->addGlobal('errors',$_SESSION['errors']);
        }

        unset($_SESSION['errors']);

        $response = $handler->handle($request);
        return $response;
    }
}
