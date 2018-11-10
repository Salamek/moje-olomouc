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
        if (preg_match('/^-?\d{2}\.\d{3,}$/', $value) === 0) {
            throw new InvalidArgumentException(sprintf('GPS value (%s) has incorrect format, only /^\d{2}\.\d{3+}$/ is allowed', $value));
        }
    }
}