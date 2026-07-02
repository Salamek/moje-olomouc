<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Enum\EntityImageContentTypeEnum;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\Tests\MojeOlomouc\BaseTest;

class EntityImageTest extends BaseTest
{
    /**
     * @param string $imageUrl
     * @param int $contentType
     * @param string $title
     * @param bool $isFeatured
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createRequiredShouldBeGoodTest(
        string $imageUrl,
        int $contentType,
        ?string $title = null,
        bool $isFeatured = false,
        ?int $id = null
    )
    {
        $entityImage = new EntityImage(
            $imageUrl
        );

        $this->assertEquals($imageUrl, $entityImage->getImageUrl());

        $this->assertEquals(EntityImageContentTypeEnum::GRAPHICS_POSTER, $entityImage->getContentType());
        $this->assertEquals(null, $entityImage->getTitle());
        $this->assertEquals(false, $entityImage->getIsFeatured());
        $this->assertEquals(null, $entityImage->getEntityIdentifier());
    }


    /**
     * @param string $imageUrl
     * @param int $contentType
     * @param string $title
     * @param bool $isFeatured
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createOptionalShouldBeGoodTest(
        string $imageUrl,
        int $contentType,
        ?string $title = null,
        bool $isFeatured = false,
        ?int $id = null
    )
    {
        $entityImage = new EntityImage(
            $imageUrl,
            $contentType,
            $title,
            $isFeatured,
            $id
        );

        $this->assertEquals($imageUrl, $entityImage->getImageUrl());

        $this->assertEquals($contentType, $entityImage->getContentType());
        $this->assertEquals($title, $entityImage->getTitle());
        $this->assertEquals($isFeatured, $entityImage->getIsFeatured());
        $this->assertEquals($id, $entityImage->getEntityIdentifier());

    }

    /**
     * @param string $imageUrl
     * @param int $contentType
     * @param string $title
     * @param bool $isFeatured
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideInvalidConstructorParameters')]

    public function createOptionalShouldFailOnBadData(
        string $imageUrl,
        int $contentType,
        ?string $title = null,
        bool $isFeatured = false,
        ?int $id = null
    )
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        new EntityImage(
            $imageUrl,
            $contentType,
            $title,
            $isFeatured,
            $id
        );
    }

    /**
     * @return array
     */

    public static function provideInvalidConstructorParameters(): array
    {
        return [
            ['url-'.mt_rand(), 192, 'title-'.mt_rand(), false, null]
        ];
    }


    /**
     * @return array
     */

    public static function provideValidConstructorParameters(): array
    {
        return [
            ['url-'.mt_rand(), EntityImageContentTypeEnum::GRAPHICS_POSTER, 'title-'.mt_rand(), false, null],
            ['url-'.mt_rand(), EntityImageContentTypeEnum::PICTURE, 'title-'.mt_rand(), false, null],
            ['url-'.mt_rand(), EntityImageContentTypeEnum::GRAPHICS_POSTER, 'title-'.mt_rand(), true, null],
            ['url-'.mt_rand(), EntityImageContentTypeEnum::GRAPHICS_POSTER, 'title-'.mt_rand(), false, mt_rand()],
        ];
    }
}