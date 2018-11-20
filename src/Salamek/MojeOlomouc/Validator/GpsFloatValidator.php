<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc\Validator;

use Salamek\MojeOlomouc\Exception\InvalidArgumentException;

/**
 * Class GpsFloatValidator
 * @package Salamek\MojeOlomouc\Validator
 */
class GpsFloatValidator
{
    public static function validate(float $value): void
    {
        // Check GPS float precision
        $requiredPrecision = 4;
        try
        {
            FloatPrecisionValidator::validate($value, $requiredPrecision);
        }
        catch (InvalidArgumentException $e)
        {
            throw new InvalidArgumentException(sprintf('GPS value (%s) has incorrect precision, precision %s or higher is allowed', $value, $requiredPrecision));
        }
    }
}