<?php

namespace App\RestApi\Calculator;

use App\RestApi\Config, App\RestApi\Error;

class Validation
{
    public static function validate(array $query, Error $errors)
    {
        foreach (Config::API_REQUIRED_FIELDS as $field) {
            if (empty($query[$field]) || !array_key_exists($field, $query)) {
                $errors->addError(Error::getErrorByCode("EMPTY_" . strtoupper($field)));
            }
        }
        if (!self::allowApi($query['apikey'], $_SERVER['REQUEST_URI'])) {
            $errors->addError(Error::getErrorByCode("WRONG_API_KEY"));
        }

        return $errors->hasError();
    }

    private static function allowApi(string $apiKey,string $url)
    {
        if (getenv('RUN_MODE', true) === 'development') {
            return true;
        }
        $exploded = explode('/', $url);
        if (!in_array($apiKey, $exploded)) {
            return false;
        }
        return true;
    }

    public static function prepareQuery(array $query)
    {
        $fieldsToUpper = ["city_to", "city_from"];
        $fieldsToFloatWithReplace = ["height", "length", "volume", "width"];
        $fieldsToFloat = ["weight"];

        foreach ($fieldsToUpper as $field) {
            $query[$field] = strtoupper($query[$field]);
        }

        foreach ($fieldsToFloatWithReplace as $field) {
            $query[$field] = floatval(str_replace(',', '.', $query[$field]));
        }

        foreach ($fieldsToFloat as $field) {
            $query[$field] = floatval($query[$field]);
        }

        if (array_key_exists(strtoupper($query['city_to']) , Config::CITY_ALIAS)) {
            $query['city_to'] = Config::getAliasByCode($query['city_to']);
        }

        if (array_key_exists(strtoupper($query['city_from']) , Config::CITY_ALIAS)) {
            $query['city_from'] = Config::getAliasByCode($query['city_from']);
        }

        return $query;
    }
}
