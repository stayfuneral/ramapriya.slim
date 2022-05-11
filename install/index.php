<?php

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\DB\Connection;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\SystemException;
use Ramapriya\Slim\Controller\ApiController;
use Ramapriya\Slim\Entity\RoutesTable;
use Ramapriya\Slim\Event\MainListener;
use Ramapriya\Slim\Interfaces\ModuleInterface;

Loc::loadMessages(__FILE__);

require dirname(__DIR__) . '/lib/interfaces/moduleinterface.php';

/**
 * @property EventManager $eventManager
 * @property Connection $connection
 */
class ramapriya_slim extends CModule
{
    public $MODULE_ID = 'ramapriya.slim';

    private array $events = [
        [
            'module' => 'main',
            'event' => 'OnPageStart',
            'class' => MainListener::class
        ]
    ];

    private array $exampleRoutes = [
        [
            'name' => 'api',
            'path' => '/api',
            'type' => 'group',
            'method' => 'group',
            'callback' => ApiController::class,
            'module' => ModuleInterface::MODULE_ID
        ]
    ];

    private array $tables = [
        RoutesTable::class
    ];

    public function __construct()
    {
        $this->MODULE_NAME = Loc::getMessage('RS_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('RS_MODULE_DESCRIPTION');

        $this->eventManager = EventManager::getInstance();
        $this->connection = Application::getConnection();
    }

    /**
     * @throws SystemException
     * @throws ArgumentException
     */
    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallEvents();
        $this->InstallDB();
    }

    public function DoUninstall()
    {
        $this->UnInstallEvents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallEvents()
    {
        foreach ($this->events as $event) {
            $this->eventManager->registerEventHandler($event['module'], $event['event'], $this->MODULE_ID, $event['class'], $event['event']);
        }
    }

    public function UnInstallEvents()
    {
        foreach ($this->events as $event) {
            $this->eventManager->unRegisterEventHandler($event['module'], $event['event'], $this->MODULE_ID, $event['class'], $event['event']);
        }
    }

    /**
     * @throws SystemException
     * @throws ArgumentException
     * @throws LoaderException
     * @throws Exception
     */
    public function InstallDB()
    {
        Loader::includeModule($this->MODULE_ID);

        /**
         * @var DataManager $table
         */
        foreach ($this->tables as $table) {
            $entity = $table::getEntity();
            $tName = $entity->getDBTableName();

            if(!$this->connection->isTableExists($tName)) {
                $entity->createDbTable();

                if($tName === RoutesTable::getTableName()) {
                    $this->installRoutes();
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    private function installRoutes()
    {
        foreach ($this->exampleRoutes as $route) {
            RoutesTable::add($route);
        }
    }


}