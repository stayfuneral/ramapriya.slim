<?php

namespace Ramapriya\Slim\Event;

use Bitrix\Main\DI\ServiceLocator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Ramapriya\Slim\App;
use Slim\Factory\AppFactory;

class MainListener
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function OnPageStart()
    {
        $container = ServiceLocator::getInstance();
        $slimApp = AppFactory::create(null, $container);

        $app = App::createInstance($slimApp, $container);
        $app->init();
    }
}