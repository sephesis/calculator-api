<?php
namespace App\RestApi\Calculator\Coefficients;

use App\RestApi\Config;

class Weight extends Coefficient
{
    const EN_CRITERIA_NAME = 'weight';

    public function getCoefficient()
    {
        $byWeight = null;
        $tarifList = $this->tarif->getTarifList($this->filter, [], false, 'both');
        foreach ($tarifList as $key => $tarif)
        {
            if (!$this->temp && $tarif->PROPERTY_TEMP_VALUE == Config::TEMP_NAME 
                || $this->temp && $tarif->PROPERTY_TEMP_VALUE != Config::TEMP_NAME) {
                continue;
            }
            if ($tarif->PROPERTY_PRICECRITERIA_VALUE == Config::CRITERIA_WEIGHT) {
                $byWeight = $tarif;
                break;
            }
        }
        $criteria = $byWeight ?: $this->checkByCriteria($this->velocity, Config::CRITERIA_WEIGHT);
    
        return $criteria;
    }
}
