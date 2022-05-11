<?php

use Bitrix\Main\Context;

return [
    'services' => [
        'value' => [
            'bitrix.request' => [
                'constructor' => static function() {
                    return Context::getCurrent()->getRequest();
                }
            ]
        ]
    ]
];