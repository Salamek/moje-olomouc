<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc\Validator;

use Salamek\MojeOlomouc\Exception\InvalidArgumentException;

/**
 * Class MinLengthValidator
 * @package Salamek\MojeOlomouc\Validator
 */
class MinLengthValidator
{
    /**
     * @param string $value
     * @param int $need
     */
    public static function validate(string $value, int $need)
    {
        if (($len = mb_strlen($value)) < $need) {
            throw new InvalidArgumentException(sprintf('value (%s) is shorter than %d characters (%d)', $value, $need, $len));
        }
    }
}