<?php
namespace App\RestApi\Calculator\Prices;

class PriceList extends Price
{
    const PRICELIST = [ 'Base', 
                        'PricePackage', 
                        'PriceDocuments', 
                        'PriceInsurance', 
                        'PricePickup', 
                        'PriceDelivery'];

    public function getPricelist(): array
    {
        foreach (self::PRICELIST as $priceType) {
            $priceClass = __NAMESPACE__ . '\\' . $priceType;
            if (class_exists($priceClass)) {
                $price = new $priceClass($this->tarif, $this->params, $this->coefficients, $this->velocity);
                $result[lcfirst($priceType)] = $price->getPrice();
            }
        }
        return $result;
    }
}
