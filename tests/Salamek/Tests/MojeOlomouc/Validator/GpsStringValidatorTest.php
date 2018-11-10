<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use PHPUnit\Framework\TestCase;
use Salamek\MojeOlomouc\Validator\GpsStringValidator;

class GpsStringValidatorTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideBadGpsString
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     */
    public function GpsStringValidatorFailOnGpsString(string $gpsString): void
    {
        GpsStringValidator::validate($gpsString);
    }

    /**
     * @test
     * @dataProvider provideGoodGpsString
     */
    public function GpsStringValidatorSuccessOnGpsString(string $gpsString): void
    {
        GpsStringValidator::validate($gpsString);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }

    /**
     * @return array
     */
    public function provideBadGpsString(): array
    {
        return [
            [''],
            ['garbage'],
            ['YOUR.MAM'],
            ['AA.MAM'],
        ];
    }

    /**
     * @return array
     */
    public function provideGoodGpsString(): array
    {
        return [
            ['49.5808551'],
            ['17.2437147'],
            ['40.7105009'],
            ['-74.0040557'],
        ];
    }
}