<?php

namespace App\Type;

class DateTime extends \DateTime
{
    public function __toString()
    {
        return $this->format('Y-m-d H:i:s.v');
    }

    public static function createFromString(string $string): DateTime
    {
        $obj = self::createFromFormat('Y-m-d H:i:s.v', $string);

        $result = new DateTime;

        $result->setDate($obj->format('Y'), $obj->format('m'), $obj->format('d'));
        $result->setTime($obj->format('H'), $obj->format('i'), $obj->format('s'), $obj->format('u'));

        return $result;
    }
}