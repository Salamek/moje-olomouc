<?php
declare(strict_types=1);

namespace Salamek\MojeOlomouc\Validator;


use Salamek\MojeOlomouc\Exception\InvalidArgumentException;

/**
 * Class FloatValidator
 * @package Salamek\MojeOlomouc\Validator
 */
class FloatPrecisionValidator
{
    /**
     * @param float $value
     * @param int $precision
     */
    public static function validate(float $value, int $precision = 4): void
    {
        $dotPosition = strpos((string)$value, '.');
        if ($dotPosition === false)
        {
            $detectedPrecision = 0;
        }
        else
        {
            $detectedPrecision = strlen(substr((string)$value, $dotPosition + 1));
        }
        
        if ($detectedPrecision < $precision)
        {
            throw new InvalidArgumentException(sprintf('Float value (%s) has incorrect precision, precision %s or higher is allowed', $value, $precision));
        }
    }
}