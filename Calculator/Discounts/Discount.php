<?php

namespace App\RestApi\Calculator\Discounts;

use \Ideta\Base\Calc;

class Discount
{
    private $instance;
    private $coefficients;
    private $price;

    public function __construct(array $coefficients, float $price) 
    {
        $this->instance = Calc::i();
        $this->coefficients = $coefficients;
        $this->price = $price;
    }

    public function getDiscount(string $cityTo, float $volume, float $weight): float
    {
        $discount = 0;
        foreach ($this->coefficients as $coefficient) {
            $coef = $coefficient->getCoefficient();
            $info[$coefficient::EN_CRITERIA_NAME]['min_price'] = (float) $coef->PROPERTY_MINPRICE_VALUE;
        }
        $byVolume = $info['volume'];
        $byWeight = $info['weight'];
        $discountParams = $this->instance->getDiscountParams($cityTo);
        if ($discountParams['amount'] > 0 && $volume <= $discountParams['volume'] 
                && $weight <= $discountParams['weight']) {
            $byVolumeMin = $info['volume']['min_price'];
            $byWeightMin = $info['weight']['min_price'];
            $discount = $this->price * ($discountParams['amount'] / 100);

            $min = $byVolume && $byWeight ? min([$byVolumeMin, $byWeightMin]) : ($byVolume ? $byVolumeMin : $byWeightMin);
            if ($this->price - $discount < $min) {
                $discount = $this->price - $min;
            }
        }
        return $discount;
    }
}
