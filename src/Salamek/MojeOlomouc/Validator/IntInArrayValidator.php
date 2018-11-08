<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc\Validator;

use Salamek\MojeOlomouc\Exception\InvalidArgumentException;

/**
 * Class InArrayValidator
 * @package Salamek\MojeOlomouc\Validator
 */
class IntInArrayValidator
{
    /**
     * @param int $value
     * @param array $allowed
     */
    public static function validate(int $value, array $allowed)
    {
        if (!in_array($value, $allowed)) {
            throw new InvalidArgumentException(sprintf('value (%s) is not in allowed array of values [%s]', $value, implode(',', $allowed)));
        }
    }
}