<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\EntityImageContentTypeEnum;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\Tests\MojeOlomouc\BaseTest;

class EntityImageTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $imageUrl
     * @param int $contentType
     * @param string $title
     * @param bool $isFeatured
     * @param int|null $id
     */
    public function createRequiredShouldBeGoodTest(
        string $imageUrl,
        int $contentType,
        string $title = null,
        bool $isFeatured = false,
        int $id = null
    )
    {
        $entityImage = new EntityImage(
            $imageUrl
        );

        $this->assertEquals($imageUrl, $entityImage->getImageUrl());

        $this->assertEquals(EntityImageContentTypeEnum::GRAPHICS_POSTER, $entityImage->getContentType());
        $this->assertEquals(null, $entityImage->getTitle());
        $this->assertEquals(false, $entityImage->getIsFeatured());
        $this->assertEquals(null, $entityImage->getId());

        $this->assertInternalType('array', $entityImage->toPrimitiveArray());


        $primitiveArrayTest = [
            'title' => $entityImage->getTitle(),
            'imageUrl' => $imageUrl,
            'contentType' => $entityImage->getContentType(),
            'isFeatured' => $entityImage->getIsFeatured()
        ];

        $this->assertEquals($primitiveArrayTest, $entityImage->toPrimitiveArray());
    }


    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $imageUrl
     * @param int $contentType
     * @param string $title
     * @param bool $isFeatured
     * @param int|null $id
     */
    public function createOptionalShouldBeGoodTest(
        string $imageUrl,
        int $contentType,
        string $title = null,
        bool $isFeatured = false,
        int $id = null
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
        $this->assertEquals($id, $entityImage->getId());

        $this->assertInternalType('array', $entityImage->toPrimitiveArray());


        $primitiveArrayTest = [
            'title' => $title,
            'imageUrl' => $imageUrl,
            'contentType' => $contentType,
            'isFeatured' => $isFeatured
        ];

        $this->assertEquals($primitiveArrayTest, $entityImage->toPrimitiveArray());
    }

    /**
     * @test
     * @dataProvider provideInvalidConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param string $imageUrl
     * @param int $contentType
     * @param string $title
     * @param bool $isFeatured
     * @param int|null $id
     */
    public function createOptionalShouldFailOnBadData(
        string $imageUrl,
        int $contentType,
        string $title = null,
        bool $isFeatured = false,
        int $id = null
    )
    {
        new EntityImage(
            $imageUrl,
            $contentType,
            $title,
            $isFeatured,
            $id
        );
    }

    /**
     * @test
     * @param string $imageUrl
     * @dataProvider provideValidConstructorParameters
     * @param int $contentType
     * @param string|null $title
     * @param bool $isFeatured
     * @param int|null $id
     */
    public function createFromRequiredPrimitiveArrayShouldBeGood(
        string $imageUrl,
        int $contentType = EntityImageContentTypeEnum::GRAPHICS_POSTER,
        string $title = null,
        bool $isFeatured = false,
        int $id = null
    )
    {

        $entityImage = EntityImage::fromPrimitiveArray(
            [
                'imageUrl' => $imageUrl,
            ]
        );

        $this->assertEquals($imageUrl, $entityImage->getImageUrl());
        $this->assertEquals(EntityImageContentTypeEnum::GRAPHICS_POSTER, $entityImage->getContentType());
        $this->assertEquals(null, $entityImage->getTitle());
        $this->assertEquals(false, $entityImage->getIsFeatured());
        $this->assertEquals(null, $entityImage->getId());
    }

    /**
     * @test
     * @param string $imageUrl
     * @dataProvider provideValidConstructorParameters
     * @param int $contentType
     * @param string|null $title
     * @param bool $isFeatured
     * @param int|null $id
     */
    public function createFromOptionalPrimitiveArrayShouldBeGood(
        string $imageUrl,
        int $contentType = EntityImageContentTypeEnum::GRAPHICS_POSTER,
        string $title = null,
        bool $isFeatured = false,
        int $id = null
    )
    {

        $entityImage = EntityImage::fromPrimitiveArray(
            [
                'imageUrl' => $imageUrl,
                'contentType' => $contentType,
                'title' => $title,
                'isFeatured' => $isFeatured,
                'id' => $id,
            ]
        );

        $this->assertEquals($imageUrl, $entityImage->getImageUrl());
        $this->assertEquals($contentType, $entityImage->getContentType());
        $this->assertEquals($title, $entityImage->getTitle());
        $this->assertEquals($isFeatured, $entityImage->getIsFeatured());
        $this->assertEquals($id, $entityImage->getId());
    }

    /**
     * @return array
     */
    public function provideInvalidConstructorParameters(): array
    {
        return [
            ['url-'.mt_rand(), 192, 'title-'.mt_rand(), false, null]
        ];
    }


    /**
     * @return array
     */
    public function provideValidConstructorParameters(): array
    {
        return [
            ['url-'.mt_rand(), EntityImageContentTypeEnum::GRAPHICS_POSTER, 'title-'.mt_rand(), false, null],
            ['url-'.mt_rand(), EntityImageContentTypeEnum::PICTURE, 'title-'.mt_rand(), false, null],
            ['url-'.mt_rand(), EntityImageContentTypeEnum::GRAPHICS_POSTER, 'title-'.mt_rand(), true, null],
            ['url-'.mt_rand(), EntityImageContentTypeEnum::GRAPHICS_POSTER, 'title-'.mt_rand(), false, mt_rand()],
        ];
    }
}