<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Validator\GpsFloatValidator;
use Salamek\MojeOlomouc\Validator\GpsLatitudeFloatValidator;

class GpsLatitudeFloatValidatorTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideBadGpsLatitudeFloat
     * @param float $gpsFloat
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     */
    public function gpsFloatValidatorFailOnGpsFloat(float $gpsFloat): void
    {
        GpsLatitudeFloatValidator::validate($gpsFloat);
    }

    /**
     * @test
     * @dataProvider provideGoodGpsLatitudeFloat
     * @param float $gpsFloat
     */
    public function gpsFloatValidatorSuccessOnGpsFloat(float $gpsFloat): void
    {
        GpsLatitudeFloatValidator::validate($gpsFloat);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }

    /**
     * @return array
     */
    public function provideBadGpsLatitudeFloat(): array
    {
        return [
            [1000],
            [99.000],
            [-99.000],
            [555.00],
            [4564.1],
        ];
    }

    /**
     * @return array
     */
    public function provideGoodGpsLatitudeFloat(): array
    {
        return [
            [49.5808551],
            [17.2437147],
            [40.7105009],
            [-74.0040557],
        ];
    }
}