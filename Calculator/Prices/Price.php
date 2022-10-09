<?php
namespace App\RestApi\Calculator\Prices;

use \Ideta\Base\Calc;
use App\RestApi\Config;

class Price
{
    protected $tarif;
    protected $params;
    protected $instance;
    protected $coefficients;
    protected $velocity;

    public function __construct(\App\RestApi\Calculator\Tariff $tarif, array $params, array $coefficients, string $velocity)
    {
        $this->tarif = $tarif;
        $this->params = $params;
        $this->instance = Calc::i();
        $this->coefficients = $coefficients;
        $this->velocity = $velocity;
    }

    public function calcTerminalEnter(?string $city, ?string $terminal = '')
    {
        $wrap = [ 'PROPERTY_SERVICE' => Config::SERVICE_TERMINAL_ENTER, 
                  'PROPERTY_POINTSTART' => $city, 
                  'PROPERTY_TERMINAL' => $terminal, 
                ];
        $tarifList = $this->tarif->getTarifList($wrap);
        $tarif = reset($tarifList);

        return $tarif->PROPERTY_PRICEPERUNIT_VALUE;
    }

    public function calcExpedition(?string $city, ?string $rsClass)
    {
        $arFilterWrap = [
            'PROPERTY_SERVICE'    => Config::SERVICE_EXPEDITION,
            'PROPERTY_POINTSTART' => $city,
            'PROPERTY_RSCLASS' => $rsClass,
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
       
        $tarifList = $this->tarif->getTarifList($arFilterWrap);

        $byVolume = $byWeight = null;
    
        foreach ($tarifList as $key => $tarif) {
            switch ($tarif->PROPERTY_PRICECRITERIA_VALUE) {
                case Config::CRITERIA_VOL:
                    $byVolume = $tarif;
                    break;
                case Config::CRITERIA_WEIGHT:
                    $byWeight = $tarif;
                    break;
            }
        }
        if (!$byVolume) {
            $tarifListV = $this->tarif->getTarifList([
                'PROPERTY_SERVICE'    => Config::SERVICE_EXPEDITION,
                'PROPERTY_POINTSTART' => $city,
                'PROPERTY_RSCLASS' => $rsClass,
                'PROPERTY_PRICECRITERIA' => Config::CRITERIA_VOL,
                '<=PROPERTY_QUANEND'      => $this->params['volume'],
            ]);

            if ($tarifListV[0]) {
                $byVolume = $tarifListV[0];
            }
        }
        if (!$byWeight) {
            $tarifListW = $this->tarif->getTarifList([
                'PROPERTY_SERVICE'    => Config::SERVICE_EXPEDITION,
                'PROPERTY_POINTSTART' => $city,
                'PROPERTY_RSCLASS' => $rsClass,
                'PROPERTY_PRICECRITERIA' => Config::CRITERIA_WEIGHT,
                '<=PROPERTY_QUANEND'      => $this->params['weight'],
            ]);
            
            if ($tarifListW[0]) {
                $byWeight = $tarifListW[0];
            }
        }

        if ($byVolume && $byWeight) {
            $byVolumePrice = max([$byVolume->PROPERTY_MINPRICE_VALUE, $byVolume->PROPERTY_PRICEPERUNIT_VALUE]);
            $byWeightPrice = max([$byWeight->PROPERTY_MINPRICE_VALUE, $byWeight->PROPERTY_PRICEPERUNIT_VALUE]);
            return max([$byVolumePrice, $byWeightPrice]);
        }

        return 0;
    }

}
