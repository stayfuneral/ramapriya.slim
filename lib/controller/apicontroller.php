<?php

namespace Ramapriya\Slim\Controller;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\Web\Json;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Ramapriya\Slim\Entity\RoutesTable;
use Ramapriya\Slim\Interfaces\RoutesGroupControllerInterface;
use Ramapriya\Slim\Services\RouterService;
use Slim\Interfaces\RouteCollectorProxyInterface;

class ApiController extends AbstractController implements RoutesGroupControllerInterface
{
    protected RouterService $routerService;

    protected array $routes = [
        'add_route' => [
            'path' => '/add_route',
            'method' => 'post',
            'callback' => 'addRoute'
        ],
        'get_routes' => [
            'path' => '/routes',
            'method' => 'get',
            'callback' => 'getRoute'
        ],
        'get_route' => [
            'path' => '/routes/{name}',
            'method' => 'get',
            'callback' => 'getRoute'
        ]
    ];

    public function __invoke(RouteCollectorProxyInterface $proxy)
    {
        $this->setRoutesGroup($proxy);
    }

    /**
     * @throws ArgumentException
     * @throws Exception
     */
    public function addRoute(ServerRequestInterface $request, ResponseInterface $response, array $args = []): ResponseInterface
    {
        $body = Json::decode((string) $request->getBody());

        $result = $this->routerService->addRoute($body);


        $response->getBody()->write($result);
        return $response;
    }

    /**
     * @throws ObjectPropertyException
     * @throws SystemException
     * @throws ArgumentException
     */
    public function getRoute(ServerRequestInterface $request, ResponseInterface $response, array $args = []): ResponseInterface
    {
        $options = [];

        if(!empty($args['name'])) {
            $options['filter']['name'] = $args['name'];
        }

        $routes = $this->routerService->getRoutes($options);
        $response->getBody()->write(
            Json::encode($routes, JSON_UNESCAPED_UNICODE)
        );

        return $response;
    }
}