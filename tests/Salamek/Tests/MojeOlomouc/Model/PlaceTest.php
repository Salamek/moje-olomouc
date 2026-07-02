<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Enum\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\Identifier;
use Salamek\MojeOlomouc\Model\Place;
use Salamek\Tests\MojeOlomouc\BaseTest;

class PlaceTest extends BaseTest
{
    /**
     * @param string $title
     * @param string $description
     * @param string $address
     * @param float $lat
     * @param float $lon
     * @param Identifier $category
     * @param array $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createRequiredShouldBeGoodTest(
        string $title,
        string $description,
        string $address,
        float $lat,
        float $lon,
        Identifier $category,
        array $images = [],
        ?string $attachmentUrl = null,
        bool $isVisible = true,
        ?int $approveState = null,
        ?int $id = null
    )
    {
        $place = new Place(
            $title,
            $description,
            $address,
            $lat,
            $lon,
            $category
        );

        $this->assertEquals($title, $place->getTitle());
        $this->assertEquals($description, $place->getDescription());
        $this->assertEquals($address, $place->getAddress());
        $this->assertEquals($lat, $place->getLat());
        $this->assertEquals($lon, $place->getLon());
        $this->assertEquals($category, $place->getCategory());
        $this->assertEquals([], $place->getImages());
        $this->assertEquals(null, $place->getAttachmentUrl());
        $this->assertEquals(null, $place->getIsVisible());
        $this->assertEquals(null, $place->getApproveState());
        $this->assertEquals(null, $place->getEntityIdentifier());
    }


    /**
     * @param string $title
     * @param string $description
     * @param string $address
     * @param float $lat
     * @param float $lon
     * @param Identifier $category
     * @param array $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideValidConstructorParameters')]

    public function createOptionalShouldBeGoodTest(
        string $title,
        string $description,
        string $address,
        float $lat,
        float $lon,
        Identifier $category,
        array $images = [],
        ?string $attachmentUrl = null,
        bool $isVisible = true,
        ?int $approveState = null,
        ?int $id = null
    )
    {
        $place = new Place(
            $title,
            $description,
            $address,
            $lat,
            $lon,
            $category,
            $images,
            $attachmentUrl,
            $isVisible,
            $approveState,
            $id
        );

        $this->assertEquals($title, $place->getTitle());
        $this->assertEquals($description, $place->getDescription());
        $this->assertEquals($address, $place->getAddress());
        $this->assertEquals($lat, $place->getLat());
        $this->assertEquals($lon, $place->getLon());
        $this->assertEquals($category, $place->getCategory());
        $this->assertEquals($images, $place->getImages());
        $this->assertEquals($attachmentUrl, $place->getAttachmentUrl());
        $this->assertEquals($isVisible, $place->getIsVisible());
        $this->assertEquals($approveState, $place->getApproveState());
        $this->assertEquals($id, $place->getEntityIdentifier());
    }

    /**
     * @param string $title
     * @param string $description
     * @param string $address
     * @param float $lat
     * @param float $lon
     * @param Identifier $category
     * @param array $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $id
     */
#[Test]
#[DataProvider('provideInvalidConstructorParameters')]

    public function createOptionalShouldFailOnBadData(
        string $title,
        string $description,
        string $address,
        float $lat,
        float $lon,
        Identifier $category,
        array $images = [],
        ?string $attachmentUrl = null,
        bool $isVisible = true,
        ?int $approveState = null,
        ?int $id = null
    )
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        new Place(
            $title,
            $description,
            $address,
            $lat,
            $lon,
            $category,
            $images,
            $attachmentUrl,
            $isVisible,
            $approveState,
            $id
        );
    }


    /**
     * @return array
     * @throws \Exception
     */

    public static function provideInvalidConstructorParameters(): array
    {
        return [
            [str_repeat('title-'.mt_rand(), 128), 'description-'.mt_rand(), 'address-'.mt_rand(), 12.16477, 12.16477, new Identifier(mt_rand()), [], 'attachmentUrl-'.mt_rand(), true, null, null],
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), 1216477, 12.16477, new Identifier(mt_rand()), [], 'attachmentUrl-'.mt_rand(), true, null, null],
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), 12.16477, 1646845646, new Identifier(mt_rand()), [], 'attachmentUrl-'.mt_rand(), true, null, null],
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), 12.16477, 12.16477, new Identifier(mt_rand()), [self::getDateTime()], 'attachmentUrl-'.mt_rand(), true, null, null],
        ];
    }


    /**
     * @return array
     */

    public static function provideValidConstructorParameters(): array
    {
        $image = new EntityImage('url');
        return [
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), 12.16477, 12.16477, new Identifier(mt_rand()), [$image], 'attachmentUrl-'.mt_rand(), true, null, null],
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), -12.16477, -12.16477, new Identifier(mt_rand()), [], null, true, ArticleApproveStateEnum::WAITING_FOR_DELETE, mt_rand()],
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), -12.16477, -12.16477, new Identifier(mt_rand()), [], null, true, ArticleApproveStateEnum::WAITING_FOR_DELETE, mt_rand()]
        ];
    }
}