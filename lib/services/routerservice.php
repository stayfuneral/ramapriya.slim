<?php

namespace Ramapriya\Slim\Services;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\DI\ServiceLocator;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\Web\Json;
use Exception;
use Ramapriya\Slim\Entity\RoutesTable;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\RouteGroupInterface;
use Slim\Routing\RouteCollectorProxy;

class RouterService
{
    private ?App $app = null;

    /**
     * @param App|null $app
     */
    public function setApp(App &$app = null): void
    {

        if($app === null) {
            $app = AppFactory::create(null, ServiceLocator::getInstance());
        }

        $this->app = &$app;
    }

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    public function getRoutes(array $options = []): array
    {
        $options['cache'] = ['ttl' => 3600];
        return  RoutesTable::getList($options)->fetchAll();
    }

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException|LoaderException
     */
    public function parseRoutes(App &$app)
    {
        if($this->app === null) {
            $this->setApp($app);
        }

        foreach ($this->getRoutes() as $route) {

            if($route['module'] !== null) {
                Loader::includeModule($route['module']);
            }

            $cb = $this->getCallback($route);

            switch ($route['type']) {
                case 'group':
                    /**
                     * @var RouteGroupInterface $group
                     */
                    $group = call_user_func_array($cb['cb'], $cb['args']);

                    if(!empty($route['middleware'])) {
                        $group->addMiddleware(new $route['middleware']);
                    }
                    break;
                case 'route':

                    /**
                     * @var RouteCollectorProxy $router
                     */
                    $router = call_user_func_array($cb['cb'], $cb['args'])->setName($route['name']);

                    if(!empty($route['middleware'])) {
                        $router->addMiddleware(new $route['middleware']);
                    }

                    break;
            }

        }
    }

    protected function getCallback(array $route): array
    {
        $callback = [
            'cb' => [$this->app, $route['method']],
            'args' => []
        ];

        switch ($route['type']) {
            case 'group':
                $callback['args'] = [$route['path'], $route['callback']];
                break;
            case 'route':

                if($route['method'] === 'map') {
                    $callback['args'][] = $route['enabled_methods'];
                }

                $callback['args'] = array_merge_recursive($callback['args'], [$route['path'], $route['callback']]);

                break;
        }

        return $callback;
    }

    /**
     * @throws Exception
     */
    public function addRoute(array $fields, array &$result = []): array
    {
        try {
            $add = RoutesTable::add($fields);

            $result['result'] = $add->isSuccess() ? 'success' : 'error';

            if($result['result'] === 'error') {
                $result['errors'] = $add->getErrorMessages();
            }
        } catch (\Throwable $e) {
            $result = [
                'result' => 'error',
                'message' => $e->getMessage()
            ];
        }

        return Json::encode($result, JSON_UNESCAPED_UNICODE);
    }
}