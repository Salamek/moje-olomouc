<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Hydrator;


use Salamek\MojeOlomouc\Enum\EntityImageContentTypeEnum;
use Salamek\Tests\MojeOlomouc\BaseTest;
use Salamek\MojeOlomouc\Hydrator\IEntityImage;

class EntityImageTest extends BaseTest
{
    /** @var IEntityImage */
    private $hydrator;

    public function setUp()
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(IEntityImage::class);
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
        $entityImage = $this->hydrator->fromPrimitiveArray(
            [
                'imageUrl' => $imageUrl,
            ]
        );
        $this->assertEquals($imageUrl, $entityImage->getImageUrl());
        $this->assertEquals(EntityImageContentTypeEnum::GRAPHICS_POSTER, $entityImage->getContentType());
        $this->assertEquals(null, $entityImage->getTitle());
        $this->assertEquals(false, $entityImage->getIsFeatured());
        $this->assertEquals(null, $entityImage->getEntityIdentifier());
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
        $entityImage = $this->hydrator->fromPrimitiveArray(
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
        $this->assertEquals($id, $entityImage->getEntityIdentifier());
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