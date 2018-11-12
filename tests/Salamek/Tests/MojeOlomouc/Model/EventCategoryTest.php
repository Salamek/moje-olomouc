<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Model\EventCategory;
use Salamek\Tests\MojeOlomouc\BaseTest;

class EventCategoryTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createRequiredShouldBeGoodTest(
        string $title,
        bool $isVisible = true,
        int $id = null
    )
    {
        $eventCategory = new EventCategory(
            $title
        );

        $this->assertEquals($title, $eventCategory->getTitle());
        $this->assertEquals(true, $eventCategory->getIsVisible());
        $this->assertEquals(null, $eventCategory->getId());
        $this->assertInternalType('array', $eventCategory->toPrimitiveArray());


        $primitiveArrayTest = [
            'title' => $title,
            'isVisible' => $eventCategory->getIsVisible()
        ];

        $this->assertEquals($primitiveArrayTest, $eventCategory->toPrimitiveArray());
    }


    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createOptionalShouldBeGoodTest(
        string $title,
        bool $isVisible = true,
        int $id = null
    )
    {
        $eventCategory = new EventCategory(
            $title,
            $isVisible,
            $id
        );

        $this->assertEquals($title, $eventCategory->getTitle());
        $this->assertEquals($isVisible, $eventCategory->getIsVisible());
        $this->assertEquals($id, $eventCategory->getId());
        $this->assertInternalType('array', $eventCategory->toPrimitiveArray());


        $primitiveArrayTest = [
            'title' => $title,
            'isVisible' => $isVisible
        ];

        $this->assertEquals($primitiveArrayTest, $eventCategory->toPrimitiveArray());
    }

    /**
     * @test
     * @dataProvider provideInvalidConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param string $title
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createOptionalShouldFailOnBadData(
        string $title,
        bool $isVisible = true,
        int $id = null
    )
    {
        new EventCategory(
            $title,
            $isVisible,
            $id
        );
    }

    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createFromRequiredPrimitiveArrayShouldBeGood(
        string $title,
        bool $isVisible = true,
        int $id = null
    )
    {
        $articleCategory = EventCategory::fromPrimitiveArray([
            'title' => $title
        ]);

        $this->assertEquals($title, $articleCategory->getTitle());
        $this->assertEquals(true, $articleCategory->getIsVisible());
        $this->assertEquals(null, $articleCategory->getId());
    }

    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createFromOptionalPrimitiveArrayShouldBeGood(
        string $title,
        bool $isVisible = true,
        int $id = null
    )
    {
        $articleCategory = EventCategory::fromPrimitiveArray([
            'title' => $title,
            'isVisible' => $isVisible,
            'id' => $id
        ]);

        $this->assertEquals($title, $articleCategory->getTitle());
        $this->assertEquals($isVisible, $articleCategory->getIsVisible());
        $this->assertEquals($id, $articleCategory->getId());
    }

    /**
     * @return array
     */
    public function provideInvalidConstructorParameters(): array
    {
        return [
            [str_repeat('title-'.mt_rand(), 128), true, null],
        ];
    }


    /**
     * @return array
     */
    public function provideValidConstructorParameters(): array
    {
        return [
            ['title-'.mt_rand(), true, null],
            ['title-'.mt_rand(), true, null],
            ['title-'.mt_rand(), true, null],
            ['title-'.mt_rand(), false, null],
            ['title-'.mt_rand(), false, mt_rand()],
        ];
    }
}