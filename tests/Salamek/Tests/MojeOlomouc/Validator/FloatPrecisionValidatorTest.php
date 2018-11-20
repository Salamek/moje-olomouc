<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Validator\FloatPrecisionValidator;

class FloatPrecisionValidatorTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideBadFloatPrecisionFloat
     * @param float $float
     * @param int $precision
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     */
    public function floatPrecisionValidatorFailOnFloat(float $float, int $precision): void
    {
        FloatPrecisionValidator::validate($float, $precision);
    }

    /**
     * @test
     * @dataProvider provideGoodFloatPrecisionFloat
     * @param float $float
     * @param int $precision
     */
    public function floatPrecisionValidatorSuccessOnFloat(float $float, int $precision): void
    {
        FloatPrecisionValidator::validate($float, $precision);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }

    /**
     * @return array
     */
    public function provideBadFloatPrecisionFloat(): array
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
    public function provideGoodFloatPrecisionFloat(): array
    {
        return [
            [49.5808551, 3],
            [17.2437147, 4],
            [40.7105009, 5],
            [-74.0040557, 7],
        ];
    }
}