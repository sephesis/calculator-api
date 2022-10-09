<?php
namespace App\RestApi\Calculator\Info;

use App\RestApi\Config;

class Type extends Info
{
    public function getInfo(): string
    {
        $type = $this->velocity == Config::VELOCITY_AUTO ? 'АВТО' : ($this->velocity == Config::VELOCITY_TRAIN ? 'ЖД' : 'ЖД Скоростной');
        return $type;
    }
}
