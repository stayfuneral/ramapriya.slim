<?php

namespace Ramapriya\Slim\Interfaces;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;

interface RoutesGroupControllerInterface
{
    public function __invoke(RouteCollectorProxyInterface $proxy);
}