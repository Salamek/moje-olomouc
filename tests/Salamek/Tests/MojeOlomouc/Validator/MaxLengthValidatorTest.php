<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 11/10/18
 * Time: 2:51 AM
 */

namespace Salamek\Tests\MojeOlomouc;


use PHPUnit\Framework\TestCase;
use Salamek\MojeOlomouc\Validator\MaxLengthValidator;

class MaxLengthValidatorTest extends BaseTest
{
    /**
     * @test
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     */
    public function MaxLengthValidatorFailOverLength(): void
    {
        MaxLengthValidator::validate(str_repeat('a', 200), 100);
    }

    /**
     * @test
     * @dataProvider provideGoodMaxLengthString
     */
    public function MaxLengthValidatorSuccessOverLength(string $maxLengthString, int $maxLength): void
    {
        MaxLengthValidator::validate($maxLengthString, $maxLength);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }

    public function provideGoodMaxLengthString(): array
    {
        return [
            [str_repeat('a', 50), 64],
            [str_repeat('a', 64), 64],
        ];
    }

}