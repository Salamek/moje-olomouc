<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Hydrator;


use Salamek\MojeOlomouc\Enum\PlaceCategoryConsumerFlagEnum;
use Salamek\MojeOlomouc\Hydrator\IPlaceCategory;
use Salamek\Tests\MojeOlomouc\BaseTest;

class PlaceCategoryTest extends BaseTest
{
    /** @var IPlaceCategory */
    private $hydrator;

    public function setUp()
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(IPlaceCategory::class);
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
        $placeCategory = $this->hydrator->fromPrimitiveArray([
            'title' => $title
        ]);
        $this->assertEquals($title, $placeCategory->getTitle());
        $this->assertEquals(null, $placeCategory->getConsumerFlags());
        $this->assertEquals(null, $placeCategory->getIsVisible());
        $this->assertEquals(null, $placeCategory->getEntityIdentifier());
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
        bool $isVisible = null,
        int $id = null
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