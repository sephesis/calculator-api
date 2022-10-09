<?php
namespace App\RestApi\Calculator\Prices;

class PriceDelivery extends Price
{
    public function getPrice(): float
    {
        if (isset($this->params['service-delivery'])) {
            foreach ($this->coefficients as $coefficient) {
                $coef = $coefficient->getCoefficient();
                if ($coefficient::EN_CRITERIA_NAME == 'volume') {
                    $info[$coefficient::EN_CRITERIA_NAME]['rs_class'] = $coef->PROPERTY_RSCLASS_VALUE;
                    $info[$coefficient::EN_CRITERIA_NAME]['rs_type'] = $coef->PROPERTY_RSTYPE_VALUE;
                    break;
                }
            }
            $price = $this->calcExpedition($this->params['city_to'], $info['volume']['rs_class']);
        }
        return (float)$price !== 0 ? (float)$price : 0;
    }
}
