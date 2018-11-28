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
        bool $isVisible = null,
        int $id = null
    )
    {
        $eventCategory = new EventCategory(
            $title
        );

        $this->assertEquals($title, $eventCategory->getTitle());
        $this->assertEquals(null, $eventCategory->getIsVisible());
        $this->assertEquals(null, $eventCategory->getEntityIdentifier());
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
        bool $isVisible = null,
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
        $this->assertEquals($id, $eventCategory->getEntityIdentifier());
    }

    /**
     * @test
     * @dataProvider provideInvalidConstructorParameters
     * @expectedException \Salamek\MojeOlomouc\Exception\InvalidArgumentException
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