<?php
namespace App\RestApi\Calculator\Info;

use App\RestApi\Config;

class Velocity extends Info
{
    public function getInfo(): string
    {
        return $this->velocity !== null ? $this->velocity : '';
    }
}
