<?php

namespace App\RestApi\Calculator\Info;

use \Ideta\Base\Calc;

class Info
{
    protected $coefficients;
    protected $velocity;
    protected $temp;

    public function __construct(array $coefficients, string $velocity, $temp = false) 
    {
        $this->coefficients = $coefficients;
        $this->velocity = $velocity;
        $this->temp = $temp;
    }
}
