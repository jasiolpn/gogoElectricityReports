<?php

namespace App\Service;

use App\Type\DateTime;

class DataMapper
{
    public function toEntity(string $entityClassName, array $data)
    {
        if (!class_exists($entityClassName))
            throw new \Exception("Invalid class \"$entityClassName\"");

        $result = new $entityClassName;

        $methods = get_class_methods($result);

        foreach ($data as $key => $value)
        {
            $method = sprintf('set%s', ucfirst($key));

            if (!in_array($method, $methods))
                throw new \Exception(sprintf('Method "%s" not found in class "%s"', $method, $entityClassName));

            $result->$method($value);
        }

        return $result;
    }

    public function dateTimeFromString($string)
    {
        $result = DateTime::createFromString($string);

        return $result;
    }
}