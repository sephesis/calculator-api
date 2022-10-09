<?php
namespace App\RestApi\Calculator\Prices;

use App\RestApi\Config;
use \Ideta\Base\Iblock\Wrap;

class PriceDocuments extends Price
{
    public function getPrice(): float
    {
        $price = 0;
        if (isset($this->params['service-documents'])) {
            $arFilterWrap = ['PROPERTY_CITY' => $this->params['city_to']];
            $list = Wrap::instance(Config::CALC_CITY_PRICES)
                        ->where($arFilterWrap)
                        ->select(['PROPERTY_PRICE_DOCS_RETURN', ])
                        ->fetch();
            $priceList = reset($list);
            $price = floatval($priceList['PROPERTY_PRICE_DOCS_RETURN_VALUE']);
        }
        return $price;
    }
}
