<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Enum\PlaceCategoryConsumerFlagEnum;
use Salamek\MojeOlomouc\Hydrator\IEntityImage;
use Salamek\MojeOlomouc\Hydrator\IPlaceCategory;
use Salamek\MojeOlomouc\Model\PlaceCategory;
use Salamek\Tests\MojeOlomouc\BaseTest;

class PlaceCategoryTest extends BaseTest
{
    /**
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createRequiredShouldBeGoodTest(
        string $title,
        ?int $consumerFlags = null,
        bool $isVisible = true,
        ?int $id = null
    )
    {
        $articleCategory = new PlaceCategory(
            $title
        );

        $this->assertEquals($title, $articleCategory->getTitle());
        $this->assertEquals(null, $articleCategory->getConsumerFlags());
        $this->assertEquals(null, $articleCategory->getIsVisible());
        $this->assertEquals(null, $articleCategory->getEntityIdentifier());

    }


    /**
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createOptionalShouldBeGoodTest(
        string $title,
        ?int $consumerFlags = null,
        ?bool $isVisible = null,
        ?int $id = null
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
        $this->assertEquals($id, $articleCategory->getEntityIdentifier());

    }

    /**
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideInvalidConstructorParameters')]

    public function createOptionalShouldFailOnBadData(
        string $title,
        ?int $consumerFlags = null,
        bool $isVisible = true,
        ?int $id = null
    )
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        new PlaceCategory(
            $title,
            $consumerFlags,
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
            [str_repeat('title-'.mt_rand(), 128), null, true, null],
        ];
    }


    /**
     * @return array
     */

    public static function provideValidConstructorParameters(): array
    {
        return [
            ['title-'.mt_rand(), null, true, null],
            ['title-'.mt_rand(), PlaceCategoryConsumerFlagEnum::CITIZEN, true, null],
            ['title-'.mt_rand(), null, true, null],
            ['title-'.mt_rand(), null, false, null],
            ['title-'.mt_rand(), null, false, mt_rand()],
        ];
    }
}
