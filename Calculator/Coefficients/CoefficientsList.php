<?php

namespace App\RestApi\Calculator\Coefficients;

class CoefficientsList extends Coefficient
{
    /** 
     * @return array
     */
    public function getList()
    {
        return [
                new Volume($this->tarif, $this->params, $this->velocity, $this->temp), 
                new Weight($this->tarif, $this->params, $this->velocity, $this->temp) , 
            ];
    }
}
