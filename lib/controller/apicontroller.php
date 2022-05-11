<?php

namespace Ramapriya\Slim\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramapriya\Slim\Interfaces\RoutesGroupControllerInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;

class ApiController extends AbstractController implements RoutesGroupControllerInterface
{

    protected array $routes = [
        'add_route' => [
            'path' => '/add_route',
            'method' => 'post',
            'callback' => 'addRoute'
        ]
    ];

    public function __invoke(RouteCollectorProxyInterface $proxy)
    {
        $this->setRoutesGroup($proxy);
    }

    public function addRoute(ServerRequestInterface $request, ResponseInterface $response, array $args = [])
    {
        
    }
}