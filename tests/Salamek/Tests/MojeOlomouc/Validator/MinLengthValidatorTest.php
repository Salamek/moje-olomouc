<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 11/10/18
 * Time: 2:52 AM
 */

namespace Salamek\Tests\MojeOlomouc;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;


use PHPUnit\Framework\TestCase;
use Salamek\MojeOlomouc\Validator\MinLengthValidator;

class MinLengthValidatorTest extends BaseTest
{
    /**
     */
#[Test]

    public function MinLengthValidatorFailOverLength(): void
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        MinLengthValidator::validate(str_repeat('a', 50), 100);
    }

    #[Test]
#[DataProvider('provideGoodMinLengthString')]

    public function MinLengthValidatorSuccessOverLength(string $minLengthString, int $minLength): void
    {
        MinLengthValidator::validate($minLengthString, $minLength);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }



    public static function provideGoodMinLengthString(): array
    {
        return [
            [str_repeat('a', 70), 64],
            [str_repeat('a', 64), 64],
        ];
    }
}