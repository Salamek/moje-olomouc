<?php
/**
 * Created by PhpStorm.
 * User: sadam
 * Date: 11/10/18
 * Time: 2:51 AM
 */

namespace Salamek\Tests\MojeOlomouc;


use PHPUnit\Framework\TestCase;
use Salamek\MojeOlomouc\Validator\IntInArrayValidator;

class IntInArrayValidatorTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideGoodIntInArrayArray
     */
    public function IntInArrayValidatorSuccess(int $int, array $intInArray): void
    {
        IntInArrayValidator::validate($int, $intInArray);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }

    /**
     * @test
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     */
    public function IntInArrayValidatorFail(): void
    {
        IntInArrayValidator::validate(1, [2, 12]);
    }

    public function provideGoodIntInArrayArray(): array
    {
        return [
            [1, [1, 2, 3, 4, 5]],
            [2, [1, 2, 3, 4, 5]],
            [3, [1, 2, 3, 4, 5]],
        ];
    }
}