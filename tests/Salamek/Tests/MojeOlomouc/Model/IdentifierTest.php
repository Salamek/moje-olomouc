<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\ArticleCategoryConsumerFlagEnum;
use Salamek\MojeOlomouc\Model\ArticleCategory;
use Salamek\MojeOlomouc\Model\Identifier;
use Salamek\Tests\MojeOlomouc\BaseTest;

class IdentifierTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param int $id
     */
    public function createRequiredShouldBeGoodTest(
        int $id
    )
    {
        $identifier = new Identifier(
            $id
        );

        $this->assertEquals($id, $identifier->getId());
        $this->assertInternalType('array', $identifier->toPrimitiveArray());


        $primitiveArrayTest = [
            'id' => $id
        ];

        $this->assertEquals($primitiveArrayTest, $identifier->toPrimitiveArray());
    }


    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param int $id
     */
    public function createFromRequiredPrimitiveArrayShouldBeGood(
        int $id
    )
    {
        $identifier = Identifier::fromPrimitiveArray([
            'id' => $id
        ]);

        $this->assertEquals($id, $identifier->getId());
    }


    /**
     * @return array
     */
    public function provideValidConstructorParameters(): array
    {
        return [
            [mt_rand()],
            [mt_rand()],
            [mt_rand()],
            [mt_rand()],
            [mt_rand()],
        ];
    }
}