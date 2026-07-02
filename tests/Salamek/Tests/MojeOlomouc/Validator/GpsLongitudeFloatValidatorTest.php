<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Validator\GpsLongitudeFloatValidator;

class GpsLongitudeFloatValidatorTest extends BaseTest
{
    /**
     * @param float $gpsFloat
     */
#[Test]
#[DataProvider('provideBadGpsLongitudeFloat')]

    public function gpsFloatValidatorFailOnGpsFloat(float $gpsFloat): void
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        GpsLongitudeFloatValidator::validate($gpsFloat);
    }

    /**
     * @param float $gpsFloat
     */
#[Test]
#[DataProvider('provideGoodGpsLongitudeFloat')]

    public function gpsFloatValidatorSuccessOnGpsFloat(float $gpsFloat): void
    {
        GpsLongitudeFloatValidator::validate($gpsFloat);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }

    /**
     * @return array
     */

    public static function provideBadGpsLongitudeFloat(): array
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

    public static function provideGoodGpsLongitudeFloat(): array
    {
        return [
            [128.5808551],
            [17.2437147],
            [40.7105009],
            [-128.0040557],
        ];
    }
}