<?php

use Bitrix\Main\IO\File;

if(File::isFileExists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

require_once __DIR__ . '/tools/functions.php';