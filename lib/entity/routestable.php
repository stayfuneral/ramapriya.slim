<?php

namespace Ramapriya\Slim\Entity;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\ArrayField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\Web\Json;

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
                ->configureUnique()
                ->configureRequired(),
            (new StringField('type'))
                ->configureRequired(),
            (new StringField('path'))
                ->configureRequired(),
            (new StringField('method'))
                ->configureRequired(),
            (new ArrayField('callback'))
                ->configureRequired(),
            (new ArrayField('enabled_methods'))
                ->configureNullable(),
            (new StringField('middleware'))
                ->configureNullable(),
            (new StringField('module'))
                ->configureNullable(),
        ];
    }
}