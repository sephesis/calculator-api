<?php
namespace App\RestApi\Calculator;

use \Ideta\Base\Iblock\Wrap;
use App\RestApi\Config;

class Tariff
{
    private $pricePropsList = [];
    private $propertyService;
    private $cityTo;
    private $cityFrom;
    private $apikey;

    public function __construct(string $cityTo, string $cityFrom, string $apikey, string $service)
    {
        $this->cityTo = $cityTo;
        $this->cityFrom = $cityFrom;
        $this->apikey = $apikey;
        $this->propertyService = $service;
        $properties = \CIBlockProperty::GetList(
            [],
            $arFilter = array(
                "ACTIVE"    => "Y",
                "IBLOCK_ID" => Config::API_IB_PRICES,
            )
        );
        while($prop = $properties->GetNext()) {
            $this->pricePropsList[] = $prop;
        }
    }

    public function getTarifList(array $params, array $additionalSelect = [], $limit = false, string $direction = '')
    {
        if (!array_key_exists('PROPERTY_POINTSTART', $params) && $direction !== '') {
            switch ($direction) {
                case 'end':
                    $defaultParams['PROPERTY_POINTEND'] = $this->cityTo;
                    break;
                case 'start':
                    $defaultParams['PROPERTY_POINTSTART'] = $this->cityFrom;
                    break;
                case 'both':
                    $defaultParams['PROPERTY_POINTEND'] = $this->cityTo;
                    $defaultParams['PROPERTY_POINTSTART'] = $this->cityFrom;
                    break;
             }
         }
         $defaultParams['PROPERTY_API_KEY'] = $this->apikey;

         if (!array_key_exists('PROPERTY_SERVICE', $params)) {
             $defaultParams['PROPERTY_SERVICE'] = $this->propertyService;
         }

        $instance = Wrap::instance(Config::API_IB_PRICES)
            ->where(array_merge($params, $defaultParams))
            ->select(array_merge($additionalSelect, array_map(function ($prop) {
                return 'PROPERTY_' . $prop['CODE'];
            },  $this->pricePropsList)));

        if ($limit) { $instance->limit($limit); }

        $tarifList = $instance->fetch();

        foreach ($tarifList as $key => $tarif) {
            $tarifList[$key] = (object)$tarif;
        }
        return $tarifList;
    }

}
