<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 11/10/18
 * Time: 2:51 AM
 */

namespace Salamek\Tests\MojeOlomouc;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;


use PHPUnit\Framework\TestCase;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;

class MaxLengthValidatorTest extends BaseTest
{
    /**
     */
#[Test]

    public function MaxLengthValidatorFailOverLength(): void
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        MaxLengthValidator::validate(str_repeat('a', 200), 100);
    }

    #[Test]
#[DataProvider('provideGoodMaxLengthString')]

    public function MaxLengthValidatorSuccessOverLength(string $maxLengthString, int $maxLength): void
    {
        MaxLengthValidator::validate($maxLengthString, $maxLength);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }

    public static function provideGoodMaxLengthString(): array
    {
        return [
            [str_repeat('a', 50), 64],
            [str_repeat('a', 64), 64],
        ];
    }

}