<?php
declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;


use Salamek\MojeOlomouc\Exception\InvalidArgumentException;
use Salamek\MojeOlomouc\Validator\ObjectArrayValidator;

class ObjectArrayValidatorTest
{
    /**
     * @test
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     */
    public function ObjectArrayValidatorFail(): void
    {
        ObjectArrayValidator::validate([
            new \DateTime(),
            new \DateTime(),
        ], InvalidArgumentException::class);
    }

    /**
     * @test
     * @dataProvider provideGoodObjectArray
     */
    public function ObjectArrayValidatorSuccess(array $array, string $type): void
    {
        ObjectArrayValidator::validate($array, $type);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }
    
    public function provideGoodObjectArray(): array
    {
        return [
            [
                [
                    new \DateTime(),
                    new \DateTime(),
                ],
                \DateTime::class
            ],
            [
                [
                    new InvalidArgumentException(),
                    new InvalidArgumentException(),
                ],
                InvalidArgumentException::class
            ],
        ];
    }
}