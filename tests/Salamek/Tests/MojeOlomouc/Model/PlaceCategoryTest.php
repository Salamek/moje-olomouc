<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\PlaceCategoryConsumerFlagEnum;
use Salamek\MojeOlomouc\Model\PlaceCategory;
use Salamek\Tests\MojeOlomouc\BaseTest;

class PlaceCategoryTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createRequiredShouldBeGoodTest(
        string $title,
        int $consumerFlags = null,
        bool $isVisible = true,
        int $id = null
    )
    {
        $articleCategory = new PlaceCategory(
            $title
        );

        $this->assertEquals($title, $articleCategory->getTitle());
        $this->assertEquals(null, $articleCategory->getConsumerFlags());
        $this->assertEquals(true, $articleCategory->getIsVisible());
        $this->assertEquals(null, $articleCategory->getId());
        $this->assertInternalType('array', $articleCategory->toPrimitiveArray());


        $primitiveArrayTest = [
            'title' => $title,
            'consumerFlags' => $articleCategory->getConsumerFlags(),
            'isVisible' => $articleCategory->getIsVisible()
        ];

        $this->assertEquals($primitiveArrayTest, $articleCategory->toPrimitiveArray());
    }


    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createOptionalShouldBeGoodTest(
        string $title,
        int $consumerFlags = null,
        bool $isVisible = true,
        int $id = null
    )
    {
        $articleCategory = new PlaceCategory(
            $title,
            $consumerFlags,
            $isVisible,
            $id
        );

        $this->assertEquals($title, $articleCategory->getTitle());
        $this->assertEquals($consumerFlags, $articleCategory->getConsumerFlags());
        $this->assertEquals($isVisible, $articleCategory->getIsVisible());
        $this->assertEquals($id, $articleCategory->getId());
        $this->assertInternalType('array', $articleCategory->toPrimitiveArray());


        $primitiveArrayTest = [
            'title' => $title,
            'consumerFlags' => $consumerFlags,
            'isVisible' => $isVisible
        ];

        $this->assertEquals($primitiveArrayTest, $articleCategory->toPrimitiveArray());
    }

    /**
     * @test
     * @dataProvider provideInvalidConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createOptionalShouldFailOnBadData(
        string $title,
        int $consumerFlags = null,
        bool $isVisible = true,
        int $id = null
    )
    {
        new PlaceCategory(
            $title,
            $consumerFlags,
            $isVisible,
            $id
        );
    }

    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createFromRequiredPrimitiveArrayShouldBeGood(
        string $title,
        int $consumerFlags = null,
        bool $isVisible = true,
        int $id = null
    )
    {
        $placeCategory = PlaceCategory::fromPrimitiveArray([
            'title' => $title
        ]);

        $this->assertEquals($title, $placeCategory->getTitle());
        $this->assertEquals(null, $placeCategory->getConsumerFlags());
        $this->assertEquals(true, $placeCategory->getIsVisible());
        $this->assertEquals(null, $placeCategory->getId());
    }

    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createFromOptionalPrimitiveArrayShouldBeGood(
        string $title,
        int $consumerFlags = null,
        bool $isVisible = true,
        int $id = null
    )
    {
        $placeCategory = PlaceCategory::fromPrimitiveArray([
            'title' => $title,
            'consumerFlags' => $consumerFlags,
            'isVisible' => $isVisible,
            'id' => $id,
        ]);

        $this->assertEquals($title, $placeCategory->getTitle());
        $this->assertEquals($consumerFlags, $placeCategory->getConsumerFlags());
        $this->assertEquals($isVisible, $placeCategory->getIsVisible());
        $this->assertEquals($id, $placeCategory->getId());
    }

    /**
     * @return array
     */
    public function provideInvalidConstructorParameters(): array
    {
        return [
            [str_repeat('title-'.mt_rand(), 128), null, false, true, null],
        ];
    }


    /**
     * @return array
     */
    public function provideValidConstructorParameters(): array
    {
        return [
            ['title-'.mt_rand(), null, false, true, null],
            ['title-'.mt_rand(), PlaceCategoryConsumerFlagEnum::CITIZEN, false, true, null],
            ['title-'.mt_rand(), null, true, true, null],
            ['title-'.mt_rand(), null, false, false, null],
            ['title-'.mt_rand(), null, false, false, mt_rand()],
        ];
    }
}