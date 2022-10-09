<?php
namespace App\RestApi\Calculator;

use \Ideta\Base\Calc, 
    Ideta\Base\Iblock\Wrap, 
    App\RestApi\Config;
use App\RestApi\Calculator\Discounts\Discount;
use App\RestApi\Calculator\Coefficients\CoefficientsList;
use App\RestApi\Calculator\Prices\PriceList;
use App\RestApi\Calculator\Validation;
use App\RestApi\Calculator\Info\InfoList;

class Calculator
{
    private $calculator;
    private $mode;
    private $params;
    private $tariff;
    private $temp;

    public function __construct(array $params)
    {
        $this->tariff = new Tariff($params['city_to'], $params['city_from'], $params['apikey'], Config::SERVICE_DELIVERY);
        $this->params = $params;
        $this->mode = isset($this->params['mode']) ? $this->params['mode'] : Config::DEFAULT_MODE;
    }

    public function calculate(): array
    {
        $result = [];
        $temperature = false;

        $this->params['apicalculator'] = !isset($this->params['apicalculator']) ? false : true;

        $cityFrom = $this->tariff->getTarifList([], [], 1, 'start');
      
        $cityTo =  $this->tariff->getTarifList([], [], 1, 'end');  
                                             
        if (count($cityFrom) && count($cityTo)) {
            if (!$this->params['apicalculator'] && $this->mode !== Config::DEFAULT_MODE) {
                if (in_array($this->mode, Config::TEMP_MODS)) { $temperature = true; }
                $result = self::calculateByMode($temperature);
            } else {
                foreach (Config::getAllMods() as $cmode) {
                    if (in_array($cmode, Config::TEMP_MODS)) { $temperature = true; }
                    $this->mode = $cmode;
                    $results[] = self::calculateByMode($temperature);
                }
                $result = call_user_func_array('array_merge', $results);
            }
        }
        return $result;
    }

    private function calculateByMode(bool $temp = false): array
    {
        $result = [];
        $coefficients = (new CoefficientsList($this->tariff, $this->params, Config::getVelocityByMode($this->mode), $temp))->getList();
        $result[$this->mode] = (new Pricelist($this->tariff, $this->params, $coefficients, Config::getVelocityByMode($this->mode)))->getPriceList();
        if ($result[$this->mode]['base'] == false) {
            $result[$this->mode] = false;
            return $result;
        }
        $result[$this->mode]['priceTotal'] = array_sum($result[$this->mode]['base']);
        if ($result[$this->mode]['priceTotal'] > 0) {
            $result[$this->mode]['info'] = (new InfoList($coefficients, Config::getVelocityByMode($this->mode), $temp))->getList();
            $result[$this->mode]['discount'] = (new Discount($coefficients, $result[$this->mode]['base']['price']))->getDiscount($this->params['city_to'], $this->params['volume'], $this->params['weight']);
        }
        $discountCoefficient = (Config::getVelocityByMode($this->mode) == Config::VELOCITY_AUTO) ? Config::VELOCITY_AUTO_COEFFICIENT : Config::DEFAULT_COEFFICIENT;
        $result[$this->mode]['sbornayaPack'] = ($result[$this->mode]['base']['price'] - $result[$this->mode]['discount']) * $discountCoefficient;
        return $result;
    }
}
