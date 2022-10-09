<?php

namespace App\RestApi;

use \Bitrix\Main\EventManager,
    \Bitrix\Main\Loader;
use Bitrix\Main\Diag\Debug;

class Application
{
     /**
     * Инициализация модулей и методов
     * @throws Exception
     */
    public static function init()
    {
        $bitrixModules = [
            "rest",
            "iblock",
            "ideta.base",
        ];

        foreach ($bitrixModules as $module) {
            if (!Loader::includeModule($module)) {
                throw new Exception("Модуль {$module} не был загружен");
            }
        }
        self::initRestMethods();
    }

    private static function initRestMethods() 
    {
        EventManager::getInstance()->addEventHandler('rest', 'OnRestServiceBuildDescription', ['App\\RestApi\\Calculator\\Request', 'desc']);
    }
}
