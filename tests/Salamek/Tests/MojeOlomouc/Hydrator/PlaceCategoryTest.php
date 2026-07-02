<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Hydrator;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;


use Salamek\MojeOlomouc\Enum\PlaceCategoryConsumerFlagEnum;
use Salamek\MojeOlomouc\Hydrator\IPlaceCategory;
use Salamek\Tests\MojeOlomouc\BaseTest;

class PlaceCategoryTest extends BaseTest
{
    /** @var IPlaceCategory */
    private $hydrator;

    public function setUp(): void
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(IPlaceCategory::class);
    }


    /**
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createFromRequiredPrimitiveArrayShouldBeGood(
        string $title,
        ?int $consumerFlags = null,
        bool $isVisible = true,
        ?int $id = null
    )
    {
        $placeCategory = $this->hydrator->fromPrimitiveArray([
            'title' => $title
        ]);
        $this->assertEquals($title, $placeCategory->getTitle());
        $this->assertEquals(null, $placeCategory->getConsumerFlags());
        $this->assertEquals(null, $placeCategory->getIsVisible());
        $this->assertEquals(null, $placeCategory->getEntityIdentifier());
    }

    /**
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createFromOptionalPrimitiveArrayShouldBeGood(
        string $title,
        ?int $consumerFlags = null,
        ?bool $isVisible = null,
        ?int $id = null
    )
    {
        $placeCategory = $this->hydrator->fromPrimitiveArray([
            'title' => $title,
            'consumerFlags' => $consumerFlags,
            'isVisible' => $isVisible,
            'id' => $id,
        ]);
        $this->assertEquals($title, $placeCategory->getTitle());
        $this->assertEquals($consumerFlags, $placeCategory->getConsumerFlags());
        $this->assertEquals($isVisible, $placeCategory->getIsVisible());
        $this->assertEquals($id, $placeCategory->getEntityIdentifier());
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
