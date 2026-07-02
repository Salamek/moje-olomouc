<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Hydrator;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;


use Salamek\MojeOlomouc\Hydrator\IArticleCategory;
use Salamek\MojeOlomouc\Hydrator\IEventCategory;
use Salamek\Tests\MojeOlomouc\BaseTest;

class EventCategoryTest extends BaseTest
{
    /** @var IArticleCategory */
    private $hydrator;

    public function setUp(): void
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(IEventCategory::class);
    }


    /**
     * @param string $title
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createFromRequiredPrimitiveArrayShouldBeGood(
        string $title,
        ?bool $isVisible = null,
        ?int $id = null
    )
    {
        $articleCategory = $this->hydrator->fromPrimitiveArray([
            'title' => $title
        ]);
        $this->assertEquals($title, $articleCategory->getTitle());
        $this->assertEquals(null, $articleCategory->getIsVisible());
        $this->assertEquals(null, $articleCategory->getEntityIdentifier());
    }

    /**
     * @param string $title
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createFromOptionalPrimitiveArrayShouldBeGood(
        string $title,
        ?bool $isVisible = null,
        ?int $id = null
    )
    {
        $articleCategory = $this->hydrator->fromPrimitiveArray([
            'title' => $title,
            'isVisible' => $isVisible,
            'id' => $id
        ]);
        $this->assertEquals($title, $articleCategory->getTitle());
        $this->assertEquals($isVisible, $articleCategory->getIsVisible());
        $this->assertEquals($id, $articleCategory->getEntityIdentifier());
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