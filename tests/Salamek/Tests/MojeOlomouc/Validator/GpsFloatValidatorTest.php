<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Validator\GpsFloatValidator;

class GpsFloatValidatorTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideBadGpsFloat
     * @param float $gpsFloat
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     */
    public function gpsFloatValidatorFailOnGpsFloat(float $gpsFloat): void
    {
        GpsFloatValidator::validate($gpsFloat);
    }

    /**
     * @test
     * @dataProvider provideGoodGpsFloat
     * @param float $gpsFloat
     */
    public function gpsFloatValidatorSuccessOnGpsFloat(float $gpsFloat): void
    {
        GpsFloatValidator::validate($gpsFloat);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }

    /**
     * @return array
     */
    public function provideBadGpsFloat(): array
    {
        return [
            [1000],
            [1.000],
            [555.00],
            [4564.1],
        ];
    }

    /**
     * @return array
     */
    public function provideGoodGpsFloat(): array
    {
        return [
            [49.5808551],
            [17.2437147],
            [40.7105009],
            [-74.0040557],
        ];
    }
}