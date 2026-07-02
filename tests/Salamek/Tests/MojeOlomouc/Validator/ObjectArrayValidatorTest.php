<?php
declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;


use Salamek\MojeOlomouc\Exception\InvalidArgumentException;
use Salamek\MojeOlomouc\Validator\ObjectArrayValidator;

class ObjectArrayValidatorTest extends BaseTest
{
    #[Test]

    public function ObjectArrayValidatorFail(): void
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        ObjectArrayValidator::validate([
            new \DateTime(),
            new \DateTime(),
        ], InvalidArgumentException::class);
    }

    #[Test]
#[DataProvider('provideGoodObjectArray')]

    public function ObjectArrayValidatorSuccess(array $array, string $type): void
    {
        ObjectArrayValidator::validate($array, $type);
        $this->assertEquals(true, true); // To fix coverage info, coverage fails when using @doesNotPerformAssertions
    }
    
    public static function provideGoodObjectArray(): array
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