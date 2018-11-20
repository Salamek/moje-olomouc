<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc\Validator;

use Salamek\MojeOlomouc\Exception\InvalidArgumentException;

/**
 * Class GpsLongitudeFloatValidator
 * @package Salamek\MojeOlomouc\Validator
 */
class GpsLongitudeFloatValidator
{
    /**
     * @param float $value
     */
    public static function validate(float $value): void
    {
        // Longitudes range from -180 to 180.
        if ($value > 180 || $value < -180)
        {
            throw new InvalidArgumentException(sprintf('GPS Longitude value (%s) is incorrect, allowed Longitudes range from -180 to 180.', $value));
        }
    }
}