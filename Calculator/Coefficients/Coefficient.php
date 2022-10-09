<?php

namespace App\RestApi\Calculator\Coefficients;

use App\RestApi\Config;

class Coefficient
{
    protected $tarif;
    protected $params;
    protected $filter = array();
    protected $velocity;
    protected $temp;


    public function __construct(\App\RestApi\Calculator\Tariff $tarif, array $params, string $velocity, bool $temp) {
        $this->tarif = $tarif;
        $this->params = $params;
        $this->velocity = $velocity;
        $this->temp = $temp;
        $this->filter = [
            'PROPERTY_VELOCITY'   => $this->velocity,
            'PROPERTY_ROUTE'      => false,
            [
                'LOGIC' => 'OR',
                [
                    'PROPERTY_PRICECRITERIA' => Config::CRITERIA_WEIGHT,
                    '<=PROPERTY_QUANSTART'   => $this->params['weight'],
                    '>PROPERTY_QUANEND'      => $this->params['weight'],
                ],
                [
                    'PROPERTY_PRICECRITERIA' => Config::CRITERIA_VOL,
                    '<=PROPERTY_QUANSTART'   => $this->params['volume'],
                    '>PROPERTY_QUANEND'      => $this->params['volume'],
                ],
            ],
        ];
    }

    protected function checkByCriteria(string $velocity, string $criteria, bool $route = false): ?object 
    {
        $tarifList = $this->tarif->getTarifList([
            'PROPERTY_VELOCITY'   => $velocity,
            'PROPERTY_ROUTE'      => $route,
            'PROPERTY_PRICECRITERIA' => $criteria,
            '<=PROPERTY_QUANEND'      => $this->params['weight'],
        ], [], false, 'both');
        foreach ($tarifList as $key => $tarif) {
            if (!$this->temp && $tarif->PROPERTY_TEMP_VALUE == Config::TEMP_NAME 
                    || $this->temp && $tarif->PROPERTY_TEMP_VALUE != Config::TEMP_NAME) {
                continue;
            }
            if ($tarif->PROPERTY_PRICECRITERIA_VALUE === $criteria) {
                $cTarif = $tarif;
                break;
            }
        }
        return $cTarif;
    }
}
