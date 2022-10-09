<?php

namespace App\RestApi\Calculator\Coefficients;

use App\RestApi\Config;

class Volume extends Coefficient
{
    const EN_CRITERIA_NAME = 'volume';

    protected $type;
    
    public function getCoefficient() 
    {
        $byVolume = null;
        $tarifList = $this->tarif->getTarifList($this->filter, [], false, 'both');
        foreach ($tarifList as $key => $tarif) {
            if ((!$this->temp && $tarif->PROPERTY_TEMP_VALUE == Config::TEMP_NAME) 
                    || ($this->temp && $tarif->PROPERTY_TEMP_VALUE !== Config::TEMP_NAME)) {
                continue;
            }
            if ($tarif->PROPERTY_PRICECRITERIA_VALUE == Config::CRITERIA_VOL) {
                $byVolume = $tarif;
                break;
            }
        }
        $criteria = $byVolume ?: $this->checkByCriteria($this->velocity, Config::CRITERIA_VOL);
        return $criteria;
    }
}
