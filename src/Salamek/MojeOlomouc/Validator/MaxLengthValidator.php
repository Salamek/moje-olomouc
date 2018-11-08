<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc\Validator;

use Salamek\MojeOlomouc\Exception\InvalidArgumentException;

/**
 * Class MaxLengthValidator
 * @package Salamek\MojeOlomouc\Validator
 */
class MaxLengthValidator
{
    /**
     * @param string $value
     * @param int $need
     */
    public static function validate(string $value, int $need)
    {
        if (($len = mb_strlen($value)) > $need) {
            throw new InvalidArgumentException(sprintf('value (%s) is longer than %d characters (%d)', $value, $need, $len));
        }
    }
}