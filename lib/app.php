<?php

namespace Ramapriya\Slim;

use Bitrix\Main\Config\Option;
use Bitrix\Main\HttpRequest;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Ramapriya\Slim\Interfaces\ModuleInterface;
use Ramapriya\Slim\Services\RouterService;
use Slim\App as SlimApp;

final class App implements ModuleInterface
{
    private static ?App $instance = null;

    private ContainerInterface $container;
    private SlimApp $app;

    public static function createInstance(SlimApp &$app = null, ContainerInterface $container = null): ?App
    {
        if(self::$instance === null) {
            self::$instance = new self($app, $container);
        }

        return self::$instance;
    }

    private function __construct(SlimApp &$app = null, ContainerInterface $container = null)
    {
        if($container !== null) {
            $this->container = $container;
        }

        if($app !== null) {
            $this->app = &$app;
        }
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Exception
     */
    public function init()
    {
        if($this->container->has('bitrix.request')) {
            /**
             * @var HttpRequest $request
             */
            $request = $this->container->get('bitrix.request');
            $rs = new RouterService();

            if(
                (
                    $this->getModuleOption('use_as_default_routing') === 'N' &&
                    $this->getModuleOption('use_as_api_routing')  === 'Y' &&
                    preg_match('/\/api\//', $request->getRequestUri(), $matches)
                ) ||
                $this->getModuleOption('use_as_default_routing')  === 'Y'
            ) {
                $rs->parseRoutes($this->app);

                $this->app->run();
                die();
            }
        }
    }

    private function getModuleOption($name): string
    {
        return Option::get(self::MODULE_ID, $name);
    }
}