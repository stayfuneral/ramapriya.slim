<?php

namespace Ramapriya\Slim\Interfaces;

use Psr\Container\ContainerInterface;

interface ControllerInterface
{
    public function __construct(ContainerInterface $container);
}