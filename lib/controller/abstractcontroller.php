<?php

namespace Ramapriya\Slim\Controller;

use Psr\Container\ContainerInterface;
use Ramapriya\Slim\Interfaces\ControllerInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;

class AbstractController implements ControllerInterface
{
    protected ContainerInterface $container;

    protected array $routes;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function setRoutesGroup(RouteCollectorProxyInterface $proxy)
    {
        if(!empty($this->routes)) {
            foreach ($this->routes as $name => $route) {
                $cb = [$proxy, $route['method']];
                $args = [$route['path'], $this->callback($route['callback'])];

                call_user_func_array($cb, $args)->setName($name);
            }
        }
    }

    protected function callback($method): array
    {
        return [$this, $method];
    }
}