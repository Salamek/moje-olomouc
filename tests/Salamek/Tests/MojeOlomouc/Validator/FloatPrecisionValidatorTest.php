<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Validator\FloatPrecisionValidator;

class FloatPrecisionValidatorTest extends BaseTest
{
    /**
     * @param float $float
     * @param int $precision
     */
#[Test]
#[DataProvider('provideBadFloatPrecisionFloat')]

    public function floatPrecisionValidatorFailOnFloat(float $float, int $precision): void
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        FloatPrecisionValidator::validate($float, $precision);
    }

    /**
     * @param float $float
     * @param int $precision
     */
#[Test]
#[DataProvider('provideGoodFloatPrecisionFloat')]

    public function floatPrecisionValidatorSuccessOnFloat(float $float, int $precision): void
    {
        FloatPrecisionValidator::validate($float, $precision);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }

    /**
     * @return array
     */

    public static function provideBadFloatPrecisionFloat(): array
    {
        return [
            [1000, 2],
            [1.000, 4],
            [555.00, 3],
            [4564.1, 2],
        ];
    }

    /**
     * @return array
     */

    public static function provideGoodFloatPrecisionFloat(): array
    {
        return [
            [49.5808551, 3],
            [17.2437147, 4],
            [40.7105009, 5],
            [-74.0040557, 7],
        ];
    }
}