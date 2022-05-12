<?php

namespace Ramapriya\Slim\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Ramapriya\Slim\Interfaces\ControllerInterface;
use Ramapriya\Slim\Services\RouterService;
use Slim\Interfaces\RouteCollectorProxyInterface;

class AbstractController implements ControllerInterface
{
    protected ContainerInterface $container;
    protected RouterService $routerService;

    protected array $routes;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->setRouterService();
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

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function setRouterService()
    {
        if($this->container->has('ramapriya.routerService')) {
            $this->routerService = $this->container->get('ramapriya.routerService');
        }
    }

    protected function callback($method): array
    {
        return [$this, $method];
    }
}