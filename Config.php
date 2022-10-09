<?php

namespace App\RestApi;

class Config
{
    const DEFAULT_MODE = "all";

    //методы
    const SCOPE = 'apicalculate';
    const SCOPE_CALCULATE = 'apicalculate.calculate.create';

    //поля для валидации
    const API_REQUIRED_FIELDS = ["city_to", "apikey", "city_from", "weight", "length", "width", "height", "volume"];

    //инфоблоки
    const API_IB_PRICES = 52;
    const CALC_CITY_PRICES = 16;

    //типы перевозок
    const VELOCITY_AUTO       = 'АВТОМОБИЛЬНАЯ';
    const VELOCITY_TRAIN      = 'ЖД';
    const VELOCITY_TRAIN_FAST = 'ЖД СКОРОСТНАЯ';

    const VELOCITY_AUTO_COEFFICIENT = 1.2;
    const DEFAULT_COEFFICIENT = 1;

    const TEMP_NAME = 'ТЕПЛЫЙ';

    const CRITERIA_VOL    = 'Объем';
    const CRITERIA_WEIGHT = 'Вес';


    const VELOCITIES = [
        'auto' => 'АВТОМОБИЛЬНАЯ',
        'train' => 'ЖД',
        'train_fast' => 'ЖД СКОРОСТНАЯ',
        'auto_temp' => 'АВТОМОБИЛЬНАЯ',
        'train_temp' => 'ЖД',
        'train_fast_temp' => 'ЖД СКОРОСТНАЯ',
    ];

    const SERVICE_DELIVERY       = 'СБОРНАЯ';
    const SERVICE_EXPEDITION     = 'АВТОВАГ';
    const SERVICE_PACKAGE        = 'УПАКОВКА';
    const SERVICE_TERMINAL_ENTER = 'ВЪЕЗДАВТО';
    const SERVICE_TERMINAL_MOVE  = 'АВТОПЕРЕМЕЩЕНИЕ';
    const SERVICE_OUT_OF_CITY    = 'ВЫЕЗД ЗА ГОРОД';

    const CITY_ALIAS = [
        'САНКТ-ПЕТЕРБУРГ' => 'С-ПЕТЕРБУРГ',
        'ПЕТРОПАВЛОВСК-КАМЧАТСКИЙ' => 'ПЕТ.КАМЧАТСК',
        'КОСТАНАЙ (КАЗАХСТАН)' => 'КОСТАНАЙ'
    ];

    const TEMP_MODS = ['auto_temp', 'train_temp', 'train_fast_temp'];

    const API_KEYS = ['y91eke8p1yz0k73x', 'l1bj2qq9xaxnx9u4', 'oa0rsy6mhgbr5m7t'];

     /**
     * Получение алиаса города по коду
     * @param string $code условный код города
     * @return string
     */
    public static function getAliasByCode($code): string
    {
        if (array_key_exists(strtoupper($code), self::CITY_ALIAS)) {
            return self::CITY_ALIAS[strtoupper($code)];
        }
        return '';
    }

     /**
     * Получение типа доставки по моду
     * @param string $mode текущий мод АПИ
     * @return string
     */
    public static function getVelocityByMode(string $mode)
    {
        if (array_key_exists($mode, self::VELOCITIES)){
            return self::VELOCITIES[$mode];
        }
        return '';
    }

     /**
     * Возвращает все моды, доступные в калькуляторе
     * @return array
     */
    public static function getAllMods()
    {
        return [
                'train', 
                'auto', 
                'train_temp', 
                'train_fast', 
                'train_fast_temp', 
                'auto_temp'
            ];
    }
}
