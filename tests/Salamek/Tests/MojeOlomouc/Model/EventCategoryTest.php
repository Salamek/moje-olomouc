<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Model\EventCategory;
use Salamek\Tests\MojeOlomouc\BaseTest;

class EventCategoryTest extends BaseTest
{
    /**
     * @param string $title
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createRequiredShouldBeGoodTest(
        string $title,
        ?bool $isVisible = null,
        ?int $id = null
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
     * @param string $title
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createOptionalShouldBeGoodTest(
        string $title,
        ?bool $isVisible = null,
        ?int $id = null
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
     * @param string $title
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideInvalidConstructorParameters')]

    public function createOptionalShouldFailOnBadData(
        string $title,
        bool $isVisible = true,
        ?int $id = null
    )
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        new EventCategory(
            $title,
            $isVisible,
            $id
        );
    }

    /**
     * @return array
     */

    public static function provideInvalidConstructorParameters(): array
    {
        return [
            [str_repeat('title-'.mt_rand(), 128), true, null],
        ];
    }


    /**
     * @return array
     */

    public static function provideValidConstructorParameters(): array
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