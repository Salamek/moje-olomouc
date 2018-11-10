<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 11/10/18
 * Time: 2:52 AM
 */

namespace Salamek\Tests\MojeOlomouc;


use PHPUnit\Framework\TestCase;
use Salamek\MojeOlomouc\Validator\MinLengthValidator;

class MinLengthValidatorTest extends BaseTest
{
    /**
     * @test
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     */
    public function MinLengthValidatorFailOverLength(): void
    {
        MinLengthValidator::validate(str_repeat('a', 50), 100);
    }

    /**
     * @test
     * @dataProvider provideGoodMinLengthString
     */
    public function MinLengthValidatorSuccessOverLength(string $minLengthString, int $minLength): void
    {
        MinLengthValidator::validate($minLengthString, $minLength);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }



    public function provideGoodMinLengthString(): array
    {
        return [
            [str_repeat('a', 70), 64],
            [str_repeat('a', 64), 64],
        ];
    }
}