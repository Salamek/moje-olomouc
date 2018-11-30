<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Hydrator;


use Salamek\MojeOlomouc\Enum\PlaceApproveStateEnum;
use Salamek\MojeOlomouc\Hydrator\IPlace;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\Tests\MojeOlomouc\BaseTest;

class PlaceTest extends BaseTest
{
    /** @var IPlace */
    private $hydrator;

    /** @var \Salamek\MojeOlomouc\Hydrator\IEntityImage */
    private $entityImageHydrator;

    public function setUp()
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(IPlace::class);
        $this->entityImageHydrator = $this->getHydrator(\Salamek\MojeOlomouc\Hydrator\IEntityImage::class);
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
        $place = $this->hydrator->fromPrimitiveArray(
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
        $this->assertEquals($categoryId, $place->getCategory()->getEntityIdentifier());
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
            $primitiveImages[] = $this->entityImageHydrator->toPrimitiveArray($image);
        }
        $place = $this->hydrator->fromPrimitiveArray(
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
        $this->assertEquals($categoryId, $place->getCategory()->getEntityIdentifier());
        $this->assertEquals($images, $place->getImages());
        $this->assertEquals($attachmentUrl, $place->getAttachmentUrl());
        $this->assertEquals($isVisible, $place->getIsVisible());
        $this->assertEquals($approveState, $place->getApproveState());
        $this->assertEquals($id, $place->getEntityIdentifier());
    }

    /**
     * @return array
     */
    public function provideValidConstructorParameters(): array
    {
        $image = new EntityImage('url');
        return [
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), 12.16477, 12.16477, mt_rand(), [$image], 'attachmentUrl-'.mt_rand(), true, null, null],
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), -12.16477, -12.16477, mt_rand(), [], null, true, PlaceApproveStateEnum::WAITING_FOR_DELETE, mt_rand()],
            ['title-'.mt_rand(), 'description-'.mt_rand(), 'address-'.mt_rand(), -12.16477, -12.16477, mt_rand(), [], null, true, PlaceApproveStateEnum::WAITING_FOR_DELETE, mt_rand()]
        ];
    }
}