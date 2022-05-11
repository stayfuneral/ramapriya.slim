<?php

namespace Ramapriya\Slim\Event;

use Bitrix\Main\DI\ServiceLocator;
use Ramapriya\Slim\App;
use Slim\Factory\AppFactory;

class MainListener
{
    public function OnPageStart()
    {
        $container = ServiceLocator::getInstance();
        $slimApp = AppFactory::create(null, $container);

        $app = App::createInstance($slimApp, $container);
        $app->init();
    }
}