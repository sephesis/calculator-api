<?php
namespace App\RestApi\Calculator\Info;

use App\RestApi\Config;

class Temp extends Info
{
    public function getInfo(): string
    {
        $coeftemp = '';
        foreach ($this->coefficients as $coefficient) {
            if ($coefficient::EN_CRITERIA_NAME == 'volume') {
                $coef = $coefficient->getCoefficient();
                $coeftemp = $coef->PROPERTY_TEMP_VALUE;
                break;
            }
        }
        return $coeftemp;
    }
}
