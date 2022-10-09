<?php

namespace App\RestApi\Calculator\Info;

class InfoList extends Info
{
    const INFO_LIST = ['Duration', 'RsType', 'Type', 'Velocity', 'Temp'];

    public function getList(): array
    {
        foreach (self::INFO_LIST as $infoType) {
            $infoClass = __NAMESPACE__ . '\\' . $infoType;
            if (class_exists($infoClass)) {
                $info = new $infoClass($this->coefficients, $this->velocity, $this->temp);
                $result[lcfirst($infoType)] = $info->getInfo();
            }
        }
        return $result;
    }
}
