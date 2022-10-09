<?php
namespace App\RestApi\Calculator\Prices;

use App\RestApi\Config;

class PricePackage extends Price
{
    public function getPrice(): float
    {
        $price = 0;
        if (isset($this->params['service-package'])) {
            $arFilterWrap = ['PROPERTY_SERVICE' => Config::SERVICE_PACKAGE,];
            $tarifList = $this->tarif->getTarifList($arFilterWrap, [], false, 'start');
            $cTarif = reset($tarifList);
            
            if (!$cTarif) {
                return 0;
            }
            $price = max([$cTarif->PROPERTY_MINPRICE_VALUE, $this->params['volume'] * $cTarif->PROPERTY_PRICEPERUNIT_VALUE]);
        }
        return $price;
    }
}
