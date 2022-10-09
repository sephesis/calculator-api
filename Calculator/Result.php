<?php

namespace App\RestApi\Calculator;

use App\RestApi\Error;
use App\RestApi\Config;

class Result
{
    private $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function init(): array
    {
        $result = [];
        if (!empty($this->params)) {
            $calculator = new Calculator($this->params);
            $result = $calculator->calculate();
        }
        return $this->getTotalResult($result);
    }

    private function getTotalResult(array $arResult): array
    {
        foreach ($arResult as $key => $values) {
            if (!$arResult[$key]) { continue; }
            $groupTransport = $arResult[$key]['sbornayaPack'];
            $discount = $arResult[$key]['discount'];
            unset($arResult[$key]['sbornayaPack']);
            unset($arResult[$key]['discount']);
            $totalPrice = array_reduce($arResult[$key], function($total, $value) {
                if (!is_array($value)) {
                    $total += $value;
                }
                return $total;
            });
            $arResult[$key]['priceTotal'] = $totalPrice;
            $arResult[$key]['discount'] = $discount;
            $arResult[$key]['sbornayaPack'] = $groupTransport;
        }
        return $arResult;

    }
}
