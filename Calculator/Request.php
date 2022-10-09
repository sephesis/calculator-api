<?php

namespace App\RestApi\Calculator;

use App\RestApi\Config, 
    App\RestApi\Error, 
    Bitrix\Rest\RestException,
    \CRestServer;

class Request extends \IRestService
{
 
    public static function desc(): array
    {
        return [Config::SCOPE => [Config::SCOPE_CALCULATE => ['callback' => [__CLASS__, 'getResult'], 'options' => array() , ], ]];
    }

    public static function getResult(array $query, $n, \CRestServer $server = null): array
    {
        $errors = new Error();
        if ($query['error']) {
            $errors->addError(Error::getErrorByCode('QUERY_ERR'));
        }
        $hasErrors = Validation::validate($query, $errors);
        if (!$hasErrors) {
            $preparedQuery = Validation::prepareQuery($query);
            $result = (new Result($preparedQuery))->init();
            return ['result' => true, 'status' => 'success', 'data' => $result, ];
        }
        throw new RestException(implode(', ', $errors->getErrors()), 500);
    }
}
