<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc\Validator;

use Salamek\MojeOlomouc\Exception\InvalidArgumentException;

/**
 * Class GpsLatitudeFloatValidator
 * @package Salamek\MojeOlomouc\Validator
 */
class GpsLatitudeFloatValidator
{
    /**
     * @param float $value
     */
    public static function validate(float $value): void
    {
        // Latitudes range from -90 to 90.
        if ($value > 90 || $value < -90)
        {
            throw new InvalidArgumentException(sprintf('GPS Latitude value (%s) is incorrect, allowed Latitudes ranges are from -90 to 90.', $value));
        }
    }
}