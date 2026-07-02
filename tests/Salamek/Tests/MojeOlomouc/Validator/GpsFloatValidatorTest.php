<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Validator\GpsFloatValidator;

class GpsFloatValidatorTest extends BaseTest
{
    /**
     * @param float $gpsFloat
     */
#[Test]
#[DataProvider('provideBadGpsFloat')]

    public function gpsFloatValidatorFailOnGpsFloat(float $gpsFloat): void
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        GpsFloatValidator::validate($gpsFloat);
    }

    /**
     * @param float $gpsFloat
     */
#[Test]
#[DataProvider('provideGoodGpsFloat')]

    public function gpsFloatValidatorSuccessOnGpsFloat(float $gpsFloat): void
    {
        GpsFloatValidator::validate($gpsFloat);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }

    /**
     * @return array
     */

    public static function provideBadGpsFloat(): array
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

    public static function provideGoodGpsFloat(): array
    {
        return [
            [49.5808551],
            [17.2437147],
            [40.7105009],
            [-74.0040557],
        ];
    }
}