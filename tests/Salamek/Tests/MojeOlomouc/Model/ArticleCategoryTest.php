<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\ArticleConsumerFlagEnum;
use Salamek\MojeOlomouc\Model\ArticleCategory;
use Salamek\Tests\MojeOlomouc\BaseTest;

class ArticleCategoryTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isImportant
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createRequiredShouldBeGoodTest(
        string $title,
        int $consumerFlags = null,
        bool $isImportant = false,
        bool $isVisible = true,
        int $id = null
    )
    {
        $articleCategory = new ArticleCategory(
            $title
        );

        $this->assertEquals($title, $articleCategory->getTitle());
        $this->assertEquals(null, $articleCategory->getConsumerFlags());
        $this->assertEquals(false, $articleCategory->getIsImportant());
        $this->assertEquals(true, $articleCategory->getIsVisible());
        $this->assertEquals(null, $articleCategory->getId());
        $this->assertInternalType('array', $articleCategory->toPrimitiveArray());


        $primitiveArrayTest = [
            'title' => $title,
            'consumerFlags' => $articleCategory->getConsumerFlags(),
            'isImportant' => $articleCategory->getIsImportant(),
            'isVisible' => $articleCategory->getIsVisible()
        ];

        $this->assertEquals($primitiveArrayTest, $articleCategory->toPrimitiveArray());
    }


    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param int|null $consumerFlags
     * @param bool $isImportant
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createOptionalShouldBeGoodTest(
        string $title,
        int $consumerFlags = null,
        bool $isImportant = false,
        bool $isVisible = true,
        int $id = null
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
        $this->assertEquals($id, $articleCategory->getId());
        $this->assertInternalType('array', $articleCategory->toPrimitiveArray());


        $primitiveArrayTest = [
            'title' => $title,
            'consumerFlags' => $consumerFlags,
            'isImportant' => $isImportant,
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
     * @param bool $isImportant
     * @param bool $isVisible
     * @param int|null $id
     */
    public function createOptionalShouldFailOnBadData(
        string $title,
        int $consumerFlags = null,
        bool $isImportant = false,
        bool $isVisible = true,
        int $id = null
    )
    {
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
            ['title-'.mt_rand(), ArticleConsumerFlagEnum::CITIZEN, false, true, null],
            ['title-'.mt_rand(), null, true, true, null],
            ['title-'.mt_rand(), null, false, false, null],
            ['title-'.mt_rand(), null, false, false, mt_rand()],
        ];
    }
}