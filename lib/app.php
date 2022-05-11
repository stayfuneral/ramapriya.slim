<?php

namespace Ramapriya\Slim;

use Psr\Container\ContainerInterface;
use Slim\App as SlimApp;

final class App
{
    private static ?App $instance = null;

    private ContainerInterface $container;
    private SlimApp $app;

    public static function createInstance(SlimApp $app = null, ContainerInterface $container = null): ?App
    {
        if(self::$instance === null) {
            self::$instance = new self($app, $container);
        }

        return self::$instance;
    }

    private function __construct(SlimApp $app = null, ContainerInterface $container = null)
    {
        if($container !== null) {
            $this->container = $container;
        }

        if($app !== null) {
            $this->app = $app;
        }
    }

    public function init()
    {

    }
}