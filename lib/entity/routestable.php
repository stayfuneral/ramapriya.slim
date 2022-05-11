<?php

namespace Ramapriya\Slim\Entity;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\ArrayField;
use Bitrix\Main\ORM\Fields\StringField;

class RoutesTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'rs_routes';
    }

    public static function getMap(): array
    {
        return [
            (new StringField('name'))
                ->configurePrimary()
                ->configureRequired(),
            (new StringField('type'))
                ->configureRequired(),
            (new StringField('path'))
                ->configureRequired(),
            (new StringField('method'))
                ->configureRequired(),
            (new StringField('callback'))
                ->configureRequired(),
            (new StringField('middleware'))
                ->configureNullable(),
            (new StringField('module'))
                ->configureNullable(),
        ];
    }
}