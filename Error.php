<?php

namespace App\RestApi;

class Error
{
    private $errors = [];
    private $hasError = false;

    const ERROR_MESSAGES = [
        "EMPTY_CITY_FROM" => "Пустой город отправления",
        "EMPTY_CITY_TO" => "Пустой город назначения",
        "EMPTY_WEIGHT" => "Не указан вес",
        "EMPTY_WIDTH" => "Не указана ширина",
        "EMPTY_LENGTH" => "Не указана длина",
        "EMPTY_HEIGHT" => "Не указана высота",
        "EMPTY_APIKEY" => "Не указан апи ключ",
        "WRONG_API_KEY" => "Неверно указан апи ключ",
        "QUERY_ERR" => "Ошибка запроса",
    ];

    public function __construct() {}


    public static function getErrorByCode(string $code)
    {
        return self::ERROR_MESSAGES[$code];
    }

    public function addError(string $error = '') 
    {
        if ($error !== '') {
            $this->errors[] = $error;
            $this->hasError = true;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasError(): bool
    {
        return $this->hasError;
    }
}
