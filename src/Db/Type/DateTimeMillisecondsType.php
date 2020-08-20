<?php

namespace App\Db\Type;

use App\Type\DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class DateTimeMillisecondsType extends Type
{
    const TYPENAME = 'datetime';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'DATETIME(3)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof DateTimeInterface)
            return $value;

        $val = DateTime::createFromString($value);

        if (!$val)
            throw ConversionException::conversionFailedFormat($value, $this->getName(), 'Y-m-d H:i:s.v');

        return $val;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value)
            return $value;

        if ($value instanceof DateTimeInterface)
            return $value->format('Y-m-d H:i:s.v');

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTime']);
    }

    public function getName()
    {
        return self::TYPENAME;
    }
}