<?php

use Bitrix\Main\Loader;

if(file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    Loader::registerNamespace('Ramapriya\\Slim\\', __DIR__ . '/lib');
}