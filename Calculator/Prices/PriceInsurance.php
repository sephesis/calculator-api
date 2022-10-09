<?php

namespace App\RestApi\Calculator\Prices;

use App\RestApi\Config;
use \Ideta\Base\Iblock\Wrap;

class PriceInsurance extends Price
{
    public function getPrice(): float
    {
        $price = 0;
        if (isset($this->params['service-insurance'])) {
            $arFilterWrap = ['PROPERTY_CITY' => $this->params['city_from'], ];
            $list = Wrap::instance(Config::CALC_CITY_PRICES)
                    ->where($arFilterWrap)
                    ->select(['PROPERTY_PRICE_INSURANCE', 'PROPERTY_PRICE_DOCS_RETURN', ])
                    ->fetch();
            $priceList = reset($list);
            $coef = floatval(str_replace(',', '.', $priceList['PROPERTY_PRICE_INSURANCE_VALUE']));
            if (isset($this->params['service-insurance']['cargo-cost'])) {
                $price = ($coef * (float)$this->params['service-insurance']['cargo-cost']) / 100;
            } else {
                $price = $coef;
            }
        }
        return $price;
    }
}
