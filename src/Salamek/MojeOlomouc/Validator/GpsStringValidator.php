<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc\Validator;

use Salamek\MojeOlomouc\Exception\InvalidArgumentException;

/**
 * Class GpsStringValidator
 * @package Salamek\MojeOlomouc\Validator
 */
class GpsStringValidator
{
    public static function validate(string $value): void
    {
        $regex = '/^-?\d{2}\.\d{3,}$/';
        if (preg_match($regex, $value) === 0) {
            throw new InvalidArgumentException(sprintf('GPS value (%s) has incorrect format, only %s is allowed', $value, $regex));
        }
    }
}