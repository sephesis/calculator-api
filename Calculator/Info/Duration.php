<?php

namespace App\RestApi\Calculator\Info;

class Duration extends Info
{
    public function getInfo(): string
    {
        $criteria = '';
        foreach ($this->coefficients as $coefficient) {
            $criteria = $coefficient::EN_CRITERIA_NAME;
            if ($criteria == 'volume') {
                $coef = $coefficient->getCoefficient();
                $info[$criteria]['duration'] = $coef->PROPERTY_DAYS_VALUE;
                break;
            }
        }
        $result = isset($info[$criteria]['duration']) ? $info[$criteria]['duration'] : '';
        return $result;
    }
}
