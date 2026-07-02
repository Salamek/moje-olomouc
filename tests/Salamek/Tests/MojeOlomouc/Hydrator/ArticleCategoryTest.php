<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Hydrator;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;


use Salamek\MojeOlomouc\Enum\ArticleCategoryConsumerFlagEnum;
use Salamek\MojeOlomouc\Hydrator\IArticleCategory;
use Salamek\Tests\MojeOlomouc\BaseTest;

class ArticleCategoryTest extends BaseTest
{
    /** @var IArticleCategory */
    private $hydrator;

    public function setUp(): void
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(IArticleCategory::class);
    }


    /**
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isImportant
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createFromRequiredPrimitiveArrayShouldBeGood(
        string $title,
        ?int $consumerFlags = null,
        ?bool $isImportant = null,
        ?bool $isVisible = null,
        ?int $id = null
    )
    {
        $articleCategory = $this->hydrator->fromPrimitiveArray([
            'title' => $title
        ]);
        $this->assertEquals($title, $articleCategory->getTitle());
        $this->assertEquals(null, $articleCategory->getConsumerFlags());
        $this->assertEquals(null, $articleCategory->getIsImportant());
        $this->assertEquals(null, $articleCategory->getIsVisible());
        $this->assertEquals(null, $articleCategory->getEntityIdentifier());
    }
    /**
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isImportant
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createFromOptionalPrimitiveArrayShouldBeGood(
        string $title,
        ?int $consumerFlags = null,
        ?bool $isImportant = null,
        ?bool $isVisible = null,
        ?int $id = null
    )
    {
        $articleCategory = $this->hydrator->fromPrimitiveArray([
            'title' => $title,
            'consumerFlags' => $consumerFlags,
            'isImportant' => $isImportant,
            'isVisible' => $isVisible,
            'id' => $id
        ]);
        $this->assertEquals($title, $articleCategory->getTitle());
        $this->assertEquals($consumerFlags, $articleCategory->getConsumerFlags());
        $this->assertEquals($isImportant, $articleCategory->getIsImportant());
        $this->assertEquals($isVisible, $articleCategory->getIsVisible());
        $this->assertEquals($id, $articleCategory->getEntityIdentifier());
    }


    /**
     * @return array
     */

    public static function provideValidConstructorParameters(): array
    {
        return [
            ['title-'.mt_rand(), null, false, true, null],
            ['title-'.mt_rand(), ArticleCategoryConsumerFlagEnum::CITIZEN, false, true, null],
            ['title-'.mt_rand(), null, true, true, null],
            ['title-'.mt_rand(), null, false, false, null],
            ['title-'.mt_rand(), null, false, false, mt_rand()],
        ];
    }

}