<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Validator\GpsLongitudeFloatValidator;

class GpsLongitudeFloatValidatorTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideBadGpsLongitudeFloat
     * @param float $gpsFloat
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     */
    public function gpsFloatValidatorFailOnGpsFloat(float $gpsFloat): void
    {
        GpsLongitudeFloatValidator::validate($gpsFloat);
    }

    /**
     * @test
     * @dataProvider provideGoodGpsLongitudeFloat
     * @param float $gpsFloat
     */
    public function gpsFloatValidatorSuccessOnGpsFloat(float $gpsFloat): void
    {
        GpsLongitudeFloatValidator::validate($gpsFloat);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }

    /**
     * @return array
     */
    public function provideBadGpsLongitudeFloat(): array
    {
        return [
            [1000],
            [189.000],
            [-198.000],
            [555.00],
            [4564.1],
        ];
    }

    /**
     * @return array
     */
    public function provideGoodGpsLongitudeFloat(): array
    {
        return [
            [128.5808551],
            [17.2437147],
            [40.7105009],
            [-128.0040557],
        ];
    }
}