<?php

namespace App\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

class CsrfFieldMiddleware implements MiddlewareInterface
{

    public function __construct(protected readonly Twig $twig,protected readonly ContainerInterface $container)
    {

    }

    public function process(\Psr\Http\Message\ServerRequestInterface $request, \Psr\Http\Server\RequestHandlerInterface $handler) :ResponseInterface
    {
        $csrf = $this->container->get('csrf');
        $csrfNameKey = $csrf->getTokenNameKey();
        $csrfValueKey = $csrf->getTokenValueKey();
        $csrfName = $csrf->getTokenName();
        $csrfValue = $csrf->getTokenValue();
        $fields = <<<CSRF_Fields
        <input type="hidden" name="$csrfNameKey" value="$csrfName">
        <input type="hidden" name="$csrfValueKey" value="$csrfValue">
        CSRF_Fields;
    
        $this->twig->getEnvironment()->addGlobal('csrf',    [
            'keys' => [
                'name'  => $csrfNameKey,
                'value' => $csrfValueKey
            ],
            'name'  => $csrfName,
            'value' => $csrfValue,
            'fields' => $fields
        ]);

        return $handler->handle($request); 
    }

}
