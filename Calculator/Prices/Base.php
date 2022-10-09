<?php
namespace App\RestApi\Calculator\Prices;

use App\RestApi\Config, 
    App\RestApi\Calculator\Discounts\Discount, 
    \Ideta\Base\Calc;

class Base extends Price
{
    public function getPrice()
    {
        $result = [];
        $priceTerminalEnter1 = 0;
        $priceTerminalEnter2 = 0;
        $skipTerminals = $route = false;
        foreach ($this->coefficients as $coefficient) {
            $coef = $coefficient->getCoefficient();
            $info[$coefficient::EN_CRITERIA_NAME]['price_per_unit'] = (float)$coef->PROPERTY_PRICEPERUNIT_VALUE;
            $info[$coefficient::EN_CRITERIA_NAME]['min_price'] = (float)$coef->PROPERTY_MINPRICE_VALUE;
            $info[$coefficient::EN_CRITERIA_NAME]['point_start'] = $coef->PROPERTY_POINTSTART_VALUE;
            $info[$coefficient::EN_CRITERIA_NAME]['point_end'] = $coef->PROPERTY_POINTEND_VALUE;
            $info[$coefficient::EN_CRITERIA_NAME]['terminal'] = $coef->PROPERTY_TERMINAL_VALUE;
            $info[$coefficient::EN_CRITERIA_NAME]['duration'] = $coef->PROPERTY_DAYS_VALUE;
        }
        
        $byWeight = $info['weight'];
        $byVolume = $info['volume'];

        if ($byVolume || $byWeight) {
            if ($byVolume && $byWeight) {
                $byVolumePrice = max([$info['volume']['min_price'], $this->params['volume'] * $info['volume']['price_per_unit']]);
                $byWeightPrice = max([$info['weight']['min_price'], $this->params['weight'] * $info['weight']['price_per_unit']]);
                $price = max([$byVolumePrice, $byWeightPrice]);
            } elseif ($byVolume) {
                $byVolumePrice = max([$info['volume']['min_price'], $this->params['volume'] * $info['volume']['price_per_unit']]);
                $price = $byVolumePrice;
            } else {
                $byWeightPrice = max([$info['weight']['min_price'], $this->params['weight'] * $info['weight']['price_per_unit']]);
                $price = $byWeightPrice;
            }
        }

        if (!$route && !$skipTerminals) {
            if ($byVolume) {
                $priceTerminalEnter1 = (float)$this->calcTerminalEnter($info['volume']['point_start'], $info['volume']['terminal']);
                $priceTerminalEnter2 = (float)$this->calcTerminalEnter($info['volume']['point_end'], $terminal ? $terminal : $info['volume']['terminal']);
            }
        }
       
        if ($price > 0) {
            $result['price'] = $price;
            $result['priceTerminalEnter1'] = $priceTerminalEnter1;
            $result['priceTerminalEnter2'] = $priceTerminalEnter2;
        } else {
            return false;
        }
        return $result;
    }
}
