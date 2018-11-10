<?php

declare(strict_types=1);

namespace Salamek\MojeOlomouc\Validator;

use Salamek\MojeOlomouc\Exception\InvalidArgumentException;

/**
 * Class ObjectArrayValidator
 * @package Salamek\MojeOlomouc\Validator
 */
class ObjectArrayValidator
{

    public static function validate(array $value, string $type): void
    {
        foreach($value AS $item)
        {
            if (!$item instanceof $type)
            {
                throw new InvalidArgumentException(sprintf('value (%s) is not instance of %s', print_r($item, true), $type));
            }
        }
    }
}