<?php

use Bitrix\Main\Context;
use Ramapriya\Slim\Services\RouterService;

return [
    'services' => [
        'value' => [
            'bitrix.request' => [
                'constructor' => static function() {
                    return Context::getCurrent()->getRequest();
                }
            ],
            'ramapriya.routerService' => [
                'className' => RouterService::class
            ]
        ]
    ]
];