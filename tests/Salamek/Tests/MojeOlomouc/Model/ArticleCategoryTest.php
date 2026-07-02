<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Enum\ArticleCategoryConsumerFlagEnum;
use Salamek\MojeOlomouc\Model\ArticleCategory;
use Salamek\Tests\MojeOlomouc\BaseTest;

class ArticleCategoryTest extends BaseTest
{
    /**
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool|null $isImportant
     * @param bool|null $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createRequiredShouldBeGoodTest(
        string $title,
        ?int $consumerFlags = null,
        ?bool $isImportant = null,
        ?bool $isVisible = null,
        ?int $id = null
    )
    {
        $articleCategory = new ArticleCategory(
            $title
        );

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

    public function createOptionalShouldBeGoodTest(
        string $title,
        ?int $consumerFlags = null,
        ?bool $isImportant = null,
        ?bool $isVisible = null,
        ?int $id = null
    )
    {
        $articleCategory = new ArticleCategory(
            $title,
            $consumerFlags,
            $isImportant,
            $isVisible,
            $id
        );

        $this->assertEquals($title, $articleCategory->getTitle());
        $this->assertEquals($consumerFlags, $articleCategory->getConsumerFlags());
        $this->assertEquals($isImportant, $articleCategory->getIsImportant());
        $this->assertEquals($isVisible, $articleCategory->getIsVisible());
        $this->assertEquals($id, $articleCategory->getEntityIdentifier());

    }

    /**
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isImportant
     * @param bool $isVisible
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideInvalidConstructorParameters')]

    public function createOptionalShouldFailOnBadData(
        string $title,
        ?int $consumerFlags = null,
        ?bool $isImportant = null,
        ?bool $isVisible = null,
        ?int $id = null
    )
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        new ArticleCategory(
            $title,
            $consumerFlags,
            $isImportant,
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
            [str_repeat('title-'.mt_rand(), 128), null, false, true, null],
        ];
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