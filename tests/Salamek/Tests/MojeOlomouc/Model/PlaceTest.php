<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\Place;
use Salamek\Tests\MojeOlomouc\BaseTest;

class PlaceTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $description
     * @param string $address
     * @param float $lat
     * @param float $lon
     * @param int $categoryId
     * @param array $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $id
     */
    public function createRequiredShouldBeGoodTest(
        string $title,
        string $description,
        string $address,
        float $lat,
        float $lon,
        int $categoryId,
        array $images = [],
        string $attachmentUrl = null,
        bool $isVisible = true,
        int $approveState = null,
        int $id = null
    )
    {
        $place = new Place(
            $title,
            $description,
            $address,
            $lat,
            $lon,
            $categoryId
        );

        $this->assertEquals($title, $place->getTitle());
        $this->assertEquals($description, $place->getDescription());
        $this->assertEquals($address, $place->getAddress());
        $this->assertEquals($lat, $place->getLat());
        $this->assertEquals($lon, $place->getLon());
        $this->assertEquals($categoryId, $place->getCategoryId());
        $this->assertEquals([], $place->getImages());
        $this->assertEquals(null, $place->getAttachmentUrl());
        $this->assertEquals(null, $place->getIsVisible());
        $this->assertEquals(null, $place->getApproveState());
        $this->assertEquals(null, $place->getEntityIdentifier());
        $this->assertInternalType('array', $place->toPrimitiveArray());


        $primitiveImages = [];
        foreach ($place->getImages() AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $primitiveArrayTest = [
            'title' => $title,
            'description' => $description,
            'address' => $address,
            'lat' => $lat,
            'lon' => $lon,
            'categoryId' => $categoryId,
            'images' => $primitiveImages
        ];

        $this->assertEquals($primitiveArrayTest, $place->toPrimitiveArray());
    }


    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $description
     * @param string $address
     * @param float $lat
     * @param float $lon
     * @param int $categoryId
     * @param array $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $id
     */
    public function createOptionalShouldBeGoodTest(
        string $title,
        string $description,
        string $address,
        float $lat,
        float $lon,
        int $categoryId,
        array $images = [],
        string $attachmentUrl = null,
        bool $isVisible = true,
        int $approveState = null,
        int $id = null
    )
    {
        $place = new Place(
            $title,
            $description,
            $address,
            $lat,
            $lon,
            $categoryId,
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
        $this->assertEquals($categoryId, $place->getCategoryId());
        $this->assertEquals($images, $place->getImages());
        $this->assertEquals($attachmentUrl, $place->getAttachmentUrl());
        $this->assertEquals($isVisible, $place->getIsVisible());
        $this->assertEquals($approveState, $place->getApproveState());
        $this->assertEquals($id, $place->getEntityIdentifier());
        $this->assertInternalType('array', $place->toPrimitiveArray());


        $primitiveImages = [];
        foreach ($images AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $primitiveArrayTest = [
            'title' => $title,
            'description' => $description,
            'address' => $address,
            'lat' => $lat,
            'lon' => $lon,
            'categoryId' => $categoryId,
            'images' => $primitiveImages
        ];

        if (!is_null($attachmentUrl)) $primitiveArrayTest['attachmentUrl'] = $attachmentUrl;
        if (!is_null($isVisible)) $primitiveArrayTest['isVisible'] = $isVisible;
        if (!is_null($approveState)) $primitiveArrayTest['approveState'] = $approveState;

        $this->assertEquals($primitiveArrayTest, $place->toPrimitiveArray());
    }

    /**
     * @test
     * @dataProvider provideInvalidConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param string $title
     * @param string $description
     * @param string $address
     * @param float $lat
     * @param float $lon
     * @param int $categoryId
     * @param array $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $id
     */
    public function createOptionalShouldFailOnBadData(
        string $title,
        string $description,
        string $address,
        float $lat,
        float $lon,
        int $categoryId,
        array $images = [],
        string $attachmentUrl = null,
        bool $isVisible = true,
        int $approveState = null,
        int $id = null
    )
    {
        new Place(
            $title,
            $description,
            $address,
            $lat,
            $lon,
            $categoryId,
            $images,
            $attachmentUrl,
            $isVisible,
            $approveState,
            $id
        );
    }

    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $description
     * @param string $address
     * @param float $lat
     * @param float $lon
     * @param int $categoryId
     * @param array $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $id
     */
    public function createFromRequiredPrimitiveArrayShouldBeGood(
        string $title,
        string $description,
        string $address,
        float $lat,
        float $lon,
        int $categoryId,
        array $images = [],
        string $attachmentUrl = null,
        bool $isVisible = true,
        int $approveState = null,
        int $id = null
    )
    {
        $place = Place::fromPrimitiveArray(
            [
                'title' => $title,
                'description' => $description,
                'address' => $address,
                'lat' => $lat,
                'lon' => $lon,
                'categoryId' => $categoryId
            ]
        );

        $this->assertEquals($title, $place->getTitle());
        $this->assertEquals($description, $place->getDescription());
        $this->assertEquals($address, $place->getAddress());
        $this->assertEquals($lat, $place->getLat());
        $this->assertEquals($lon, $place->getLon());
        $this->assertEquals($categoryId, $place->getCategoryId());
        $this->assertEquals([], $place->getImages());
        $this->assertEquals(null, $place->getAttachmentUrl());
        $this->assertEquals(null, $place->getIsVisible());
        $this->assertEquals(null, $place->getApproveState());
        $this->assertEquals(null, $place->getEntityIdentifier());
    }

    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $description
     * @param string $address
     * @param float $lat
     * @param float $lon
     * @param int $categoryId
     * @param array $images
     * @param string|null $attachmentUrl
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $id
     */
    public function createFromOptionalPrimitiveArrayShouldBeGood(
        string $title,
        string $description,
        string $address,
        float $lat,
        float $lon,
        int $categoryId,
        array $images = [],
        string $attachmentUrl = null,
        bool $isVisible = true,
        int $approveState = null,
        int $id = null
    )
    {
        $primitiveImages = [];
        foreach($images AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $place = Place::fromPrimitiveArray(
            [
                'title' => $title,
                'description' => $description,
                'address' => $address,
                'lat' => $lat,
                'lon' => $lon,
                'categoryId' => $categoryId,
                'images' => $primitiveImages,
                'attachmentUrl' => $attachmentUrl,
                'isVisible' => $isVisible,
                'approveState' => $approveState,
                'id' => $id,
            ]
        );

        $this->assertEquals($title, $place->getTitle());
        $this->assertEquals($description, $place->getDescription());
        $this->assertEquals($address, $place->getAddress());
        $this->assertEquals($lat, $place->getLat());
        $this->assertEquals($lon, $place->getLon());
        $this->assertEquals($categoryId, $place->getCategoryId());
        $this->assertEquals($images, $place->getImages());
        $this->assertEquals($attachmentUrl, $place->getAttachmentUrl());
        $this->assertEquals($isVisible, $place->getIsVisible());
        $this->assertEquals($approveState, $place->getApproveState());
        $this->assertEquals($id, $place->getEntityIdentifier());
    }

    /**
     * @return array
     */
    public function provideInvalidConstructorParameters(): array
    {
        return [
            [str_repeat('title-'.mt_rand(), 128), 'description-'.mt_rand(), 'address-'.mt_rand(), 12.16477, 12.16477, mt_rand(), [], 'attachmentUrl-'.mt_rand(), true, null, null],
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), 1216477, 12.16477, mt_rand(), [], 'attachmentUrl-'.mt_rand(), true, null, null],
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), 12.16477, 1646845646, mt_rand(), [], 'attachmentUrl-'.mt_rand(), true, null, null],
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), 12.16477, 12.16477, mt_rand(), [$this->getDateTime()], 'attachmentUrl-'.mt_rand(), true, null, null],
        ];
    }


    /**
     * @return array
     */
    public function provideValidConstructorParameters(): array
    {
        $image = new EntityImage('url');
        return [
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), 12.16477, 12.16477, mt_rand(), [$image], 'attachmentUrl-'.mt_rand(), true, null, null],
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), -12.16477, -12.16477, mt_rand(), [], null, true, ArticleApproveStateEnum::WAITING_FOR_DELETE, mt_rand()],
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), -12.16477, -12.16477, mt_rand(), [], null, true, ArticleApproveStateEnum::WAITING_FOR_DELETE, mt_rand()]
        ];
    }
}