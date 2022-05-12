<?php

use Bitrix\Main\IO\File;
use Bitrix\Main\IO\FileNotFoundException;

if(!function_exists('view')) {

    /**
     * @throws FileNotFoundException
     */
    function view(string $template) {

        $path = sprintf('%s/views/%s', dirname(__DIR__), $template);

        if(File::isFileExists($path)) {
            require $path;
        }

        throw new FileNotFoundException($path);
    }
}