<?php

namespace App\RestApi\Calculator\Info;

class RsType extends Info
{
    public function getInfo(): string
    {
        foreach ($this->coefficients as $coefficient) {
            $criteria = $coefficient::EN_CRITERIA_NAME;
            if ($criteria == 'volume') {
                $coef = $coefficient->getCoefficient();
                $info[$criteria]['rstype'] = $coef->PROPERTY_RSTYPE_VALUE;
                break;
            }
        }
        $result = isset($info[$criteria]['rstype']) ? $info[$criteria]['rstype'] : '';
        return $result;
    }
}
