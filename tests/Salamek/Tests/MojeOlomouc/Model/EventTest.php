<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc\Model;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\EventApproveStateEnum;
use Salamek\MojeOlomouc\Enum\EventConsumerFlagEnum;
use Salamek\MojeOlomouc\Enum\EventFeaturedLevelEnum;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\Event;
use Salamek\Tests\MojeOlomouc\BaseTest;

class EventTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $description
     * @param \DateTimeInterface $startAt
     * @param \DateTimeInterface $endAt
     * @param string $placeDesc
     * @param float $placeLat
     * @param float $placeLon
     * @param array $categoryIdsArr
     * @param array $images
     * @param string|null $attachmentUrl
     * @param string|null $fee
     * @param string|null $webUrl
     * @param string|null $facebookUrl
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $featuredLevel
     * @param int|null $id
     */
    public function createRequiredShouldBeGoodTest(
        string $title,
        string $description,
        \DateTimeInterface $startAt,
        \DateTimeInterface $endAt,
        string $placeDesc,
        float $placeLat,
        float $placeLon,
        array $categoryIdsArr,
        array $images = [],
        string $attachmentUrl = null,
        string $fee = null,
        string $webUrl = null,
        string $facebookUrl = null,
        int $consumerFlags = null,
        bool $isVisible = true,
        int $approveState = null,
        int $featuredLevel = null,
        int $id = null
    )
    {
        $event = new Event(
             $title,
             $description,
             $startAt,
             $endAt,
             $placeDesc,
             $placeLat,
             $placeLon,
             $categoryIdsArr
        );

        $this->assertEquals($title, $event->getTitle());
        $this->assertEquals($description, $event->getDescription());
        $this->assertEquals($startAt, $event->getStartAt());
        $this->assertEquals($endAt, $event->getEndAt());
        $this->assertEquals($placeDesc, $event->getPlaceDesc());
        $this->assertEquals($placeLat, $event->getPlaceLat());
        $this->assertEquals($placeLon, $event->getPlaceLon());
        $this->assertEquals($categoryIdsArr, $event->getCategoryIdsArr());
        $this->assertEquals([], $event->getImages());
        $this->assertEquals(null, $event->getAttachmentUrl());
        $this->assertEquals(null, $event->getFee());
        $this->assertEquals(null, $event->getWebUrl());
        $this->assertEquals(null, $event->getFacebookUrl());
        $this->assertEquals(null, $event->getConsumerFlags());
        $this->assertEquals(null, $event->getIsVisible());
        $this->assertEquals(null, $event->getApproveState());
        $this->assertEquals(null, $event->getFeaturedLevel());
        $this->assertEquals(null, $event->getEntityIdentifier());
        $this->assertInternalType('array', $event->toPrimitiveArray());


        $primitiveImages = [];
        foreach ($event->getImages() AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $primitiveArrayTest = [
            'title' => $title,
            'description' => $description,
            'startAt' => $startAt->format(DateTime::NOT_A_ISO8601),
            'endAt' => $endAt->format(DateTime::NOT_A_ISO8601),
            'placeDesc' => $placeDesc,
            'placeLat' => $placeLat,
            'placeLon' => $placeLon,
            'categoryIdsArr' => $categoryIdsArr,
            'images'   => $primitiveImages
        ];

        $this->assertEquals($primitiveArrayTest, $event->toPrimitiveArray());
    }


    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $description
     * @param \DateTimeInterface $startAt
     * @param \DateTimeInterface $endAt
     * @param string $placeDesc
     * @param float $placeLat
     * @param float $placeLon
     * @param array $categoryIdsArr
     * @param array $images
     * @param string|null $attachmentUrl
     * @param string|null $fee
     * @param string|null $webUrl
     * @param string|null $facebookUrl
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $featuredLevel
     * @param int|null $id
     */
    public function createOptionalShouldBeGoodTest(
        string $title,
        string $description,
        \DateTimeInterface $startAt,
        \DateTimeInterface $endAt,
        string $placeDesc,
        float $placeLat,
        float $placeLon,
        array $categoryIdsArr,
        array $images = [],
        string $attachmentUrl = null,
        string $fee = null,
        string $webUrl = null,
        string $facebookUrl = null,
        int $consumerFlags = null,
        bool $isVisible = true,
        int $approveState = null,
        int $featuredLevel = null,
        int $id = null
    )
    {
        $event = new Event(
            $title,
            $description,
            $startAt,
            $endAt,
            $placeDesc,
            $placeLat,
            $placeLon,
            $categoryIdsArr,
            $images,
            $attachmentUrl,
            $fee,
            $webUrl,
            $facebookUrl,
            $consumerFlags,
            $isVisible,
            $approveState,
            $featuredLevel,
            $id
        );

        $this->assertEquals($title, $event->getTitle());
        $this->assertEquals($description, $event->getDescription());
        $this->assertEquals($startAt, $event->getStartAt());
        $this->assertEquals($endAt, $event->getEndAt());
        $this->assertEquals($placeDesc, $event->getPlaceDesc());
        $this->assertEquals($placeLat, $event->getPlaceLat());
        $this->assertEquals($placeLon, $event->getPlaceLon());
        $this->assertEquals($categoryIdsArr, $event->getCategoryIdsArr());
        $this->assertEquals($images, $event->getImages());
        $this->assertEquals($attachmentUrl, $event->getAttachmentUrl());
        $this->assertEquals($fee, $event->getFee());
        $this->assertEquals($webUrl, $event->getWebUrl());
        $this->assertEquals($facebookUrl, $event->getFacebookUrl());
        $this->assertEquals($consumerFlags, $event->getConsumerFlags());
        $this->assertEquals($isVisible, $event->getIsVisible());
        $this->assertEquals($approveState, $event->getApproveState());
        $this->assertEquals($featuredLevel, $event->getFeaturedLevel());
        $this->assertEquals($id, $event->getEntityIdentifier());
        $this->assertInternalType('array', $event->toPrimitiveArray());


        $primitiveImages = [];
        foreach ($images AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $primitiveArrayTest = [
            'title' => $title,
            'description' => $description,
            'startAt' => $startAt->format(DateTime::NOT_A_ISO8601),
            'endAt' => $endAt->format(DateTime::NOT_A_ISO8601),
            'placeDesc' => $placeDesc,
            'placeLat' => $placeLat,
            'placeLon' => $placeLon,
            'categoryIdsArr' => $categoryIdsArr,
            'images'   => $primitiveImages
        ];

        if (!is_null($attachmentUrl)) $primitiveArrayTest['attachmentUrl'] = $attachmentUrl;
        if (!is_null($fee)) $primitiveArrayTest['fee'] = $fee;
        if (!is_null($webUrl)) $primitiveArrayTest['webUrl'] = $webUrl;
        if (!is_null($facebookUrl)) $primitiveArrayTest['facebookUrl'] = $facebookUrl;
        if (!is_null($consumerFlags)) $primitiveArrayTest['consumerFlags'] = $consumerFlags;
        if (!is_null($isVisible)) $primitiveArrayTest['isVisible'] = $isVisible;
        if (!is_null($approveState)) $primitiveArrayTest['approveState'] = $approveState;
        if (!is_null($featuredLevel)) $primitiveArrayTest['featuredLevel'] = $featuredLevel;

        $this->assertEquals($primitiveArrayTest, $event->toPrimitiveArray());
    }

    /**
     * @test
     * @dataProvider provideInvalidConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param string $title
     * @param string $description
     * @param \DateTimeInterface $startAt
     * @param \DateTimeInterface $endAt
     * @param string $placeDesc
     * @param float $placeLat
     * @param float $placeLon
     * @param array $categoryIdsArr
     * @param array $images
     * @param string|null $attachmentUrl
     * @param string|null $fee
     * @param string|null $webUrl
     * @param string|null $facebookUrl
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $featuredLevel
     * @param int|null $id
     */
    public function createOptionalShouldFailOnBadData(
        string $title,
        string $description,
        \DateTimeInterface $startAt,
        \DateTimeInterface $endAt,
        string $placeDesc,
        float $placeLat,
        float $placeLon,
        array $categoryIdsArr,
        array $images = [],
        string $attachmentUrl = null,
        string $fee = null,
        string $webUrl = null,
        string $facebookUrl = null,
        int $consumerFlags = null,
        bool $isVisible = true,
        int $approveState = null,
        int $featuredLevel = null,
        int $id = null
    )
    {
        new Event(
            $title,
            $description,
            $startAt,
            $endAt,
            $placeDesc,
            $placeLat,
            $placeLon,
            $categoryIdsArr,
            $images,
            $attachmentUrl,
            $fee,
            $webUrl,
            $facebookUrl,
            $consumerFlags,
            $isVisible,
            $approveState,
            $featuredLevel,
            $id
        );
    }

    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $description
     * @param \DateTimeInterface $startAt
     * @param \DateTimeInterface $endAt
     * @param string $placeDesc
     * @param float $placeLat
     * @param float $placeLon
     * @param array $categoryIdsArr
     * @param array $images
     * @param string|null $attachmentUrl
     * @param string|null $fee
     * @param string|null $webUrl
     * @param string|null $facebookUrl
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $featuredLevel
     * @param int|null $id
     */
    public function createFromRequiredPrimitiveArrayShouldBeGood(
        string $title,
        string $description,
        \DateTimeInterface $startAt,
        \DateTimeInterface $endAt,
        string $placeDesc,
        float $placeLat,
        float $placeLon,
        array $categoryIdsArr,
        array $images = [],
        string $attachmentUrl = null,
        string $fee = null,
        string $webUrl = null,
        string $facebookUrl = null,
        int $consumerFlags = null,
        bool $isVisible = true,
        int $approveState = null,
        int $featuredLevel = null,
        int $id = null
    )
    {
        $event = Event::fromPrimitiveArray(
            [
                'title' => $title,
                'description' => $description,
                'startAt' => $startAt->format(DateTime::NOT_A_ISO8601),
                'endAt' => $endAt->format(DateTime::NOT_A_ISO8601),
                'placeDesc' => $placeDesc,
                'placeLat' => $placeLat,
                'placeLon' => $placeLon,
                'categoryIdsArr' => $categoryIdsArr
            ]
        );

        $this->assertEquals($title, $event->getTitle());
        $this->assertEquals($description, $event->getDescription());
        $this->assertEquals($startAt, $event->getStartAt());
        $this->assertEquals($endAt, $event->getEndAt());
        $this->assertEquals($placeDesc, $event->getPlaceDesc());
        $this->assertEquals($placeLat, $event->getPlaceLat());
        $this->assertEquals($placeLon, $event->getPlaceLon());
        $this->assertEquals($categoryIdsArr, $event->getCategoryIdsArr());
        $this->assertEquals([], $event->getImages());
        $this->assertEquals(null, $event->getAttachmentUrl());
        $this->assertEquals(null, $event->getFee());
        $this->assertEquals(null, $event->getWebUrl());
        $this->assertEquals(null, $event->getFacebookUrl());
        $this->assertEquals(null, $event->getConsumerFlags());
        $this->assertEquals(null, $event->getIsVisible());
        $this->assertEquals(null, $event->getApproveState());
        $this->assertEquals(null, $event->getFeaturedLevel());
        $this->assertEquals(null, $event->getEntityIdentifier());
    }

    /**
     * @test
     * @dataProvider provideValidConstructorParameters
     * @param string $title
     * @param string $description
     * @param \DateTimeInterface $startAt
     * @param \DateTimeInterface $endAt
     * @param string $placeDesc
     * @param float $placeLat
     * @param float $placeLon
     * @param array $categoryIdsArr
     * @param array $images
     * @param string|null $attachmentUrl
     * @param string|null $fee
     * @param string|null $webUrl
     * @param string|null $facebookUrl
     * @param int|null $consumerFlags
     * @param bool $isVisible
     * @param int|null $approveState
     * @param int|null $featuredLevel
     * @param int|null $id
     */
    public function createFromOptionalPrimitiveArrayShouldBeGood(
        string $title,
        string $description,
        \DateTimeInterface $startAt,
        \DateTimeInterface $endAt,
        string $placeDesc,
        float $placeLat,
        float $placeLon,
        array $categoryIdsArr,
        array $images = [],
        string $attachmentUrl = null,
        string $fee = null,
        string $webUrl = null,
        string $facebookUrl = null,
        int $consumerFlags = null,
        bool $isVisible = true,
        int $approveState = null,
        int $featuredLevel = null,
        int $id = null
    )
    {
        $primitiveImages = [];
        foreach($images AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $event = Event::fromPrimitiveArray(
            [
                'title' => $title,
                'description' => $description,
                'startAt' => $startAt->format(DateTime::NOT_A_ISO8601),
                'endAt' => $endAt->format(DateTime::NOT_A_ISO8601),
                'placeDesc' => $placeDesc,
                'placeLat' => $placeLat,
                'placeLon' => $placeLon,
                'categoryIdsArr' => $categoryIdsArr,
                'images' => $primitiveImages,
                'attachmentUrl' => $attachmentUrl,
                'fee' => $fee,
                'webUrl' => $webUrl,
                'facebookUrl' => $facebookUrl,
                'consumerFlags' => $consumerFlags,
                'isVisible' => $isVisible,
                'approveState' => $approveState,
                'featuredLevel' => $featuredLevel,
                'id' => $id
            ]
        );

        $this->assertEquals($title, $event->getTitle());
        $this->assertEquals($description, $event->getDescription());
        $this->assertEquals($startAt, $event->getStartAt());
        $this->assertEquals($endAt, $event->getEndAt());
        $this->assertEquals($placeDesc, $event->getPlaceDesc());
        $this->assertEquals($placeLat, $event->getPlaceLat());
        $this->assertEquals($placeLon, $event->getPlaceLon());
        $this->assertEquals($categoryIdsArr, $event->getCategoryIdsArr());
        $this->assertEquals($images, $event->getImages());
        $this->assertEquals($attachmentUrl, $event->getAttachmentUrl());
        $this->assertEquals($fee, $event->getFee());
        $this->assertEquals($webUrl, $event->getWebUrl());
        $this->assertEquals($facebookUrl, $event->getFacebookUrl());
        $this->assertEquals($consumerFlags, $event->getConsumerFlags());
        $this->assertEquals($isVisible, $event->getIsVisible());
        $this->assertEquals($approveState, $event->getApproveState());
        $this->assertEquals($featuredLevel, $event->getFeaturedLevel());
        $this->assertEquals($id, $event->getEntityIdentifier());
    }

    /**
     * @return array
     */
    public function provideInvalidConstructorParameters(): array
    {
        return [
            [
                str_repeat('title-'.mt_rand(), 128),
                'content'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc'.mt_rand(),
                12.5467678,
                -22.5467678,
                [1, 2, 3],
                [],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                null,
                true,
                null,
                null,
                null
            ],
            [
                'title-'.mt_rand(),
                'content'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc'.mt_rand(),
                102.5467678,
                -22.5467678,
                [1, 2, 3],
                [],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                null,
                true,
                null,
                null,
                null
            ],
            [
                'title-'.mt_rand(),
                'content'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc'.mt_rand(),
                12.5467678,
                -202.5467678,
                [1, 2, 3],
                [],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                null,
                true,
                null,
                null,
                null
            ],
            [
                'title-'.mt_rand(),
                'content'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc'.mt_rand(),
                12.5467678,
                -202.5467678,
                [],
                [],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                null,
                true,
                null,
                null,
                null
            ],
            [
                'title-'.mt_rand(),
                'content'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc'.mt_rand(),
                12.5467678,
                -202.5467678,
                [],
                [$this->getDateTime()],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                null,
                true,
                null,
                null,
                null
            ],
            [
                'title-'.mt_rand(),
                'content'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc'.mt_rand(),
                12.5467678,
                -202.5467678,
                [],
                [],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                1024,
                true,
                null,
                null,
                null
            ],
            [
                'title-'.mt_rand(),
                'content'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc'.mt_rand(),
                12.5467678,
                -202.5467678,
                [],
                [],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                null,
                true,
                1024,
                null,
                null
            ],[
                'title-'.mt_rand(),
                'content'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc'.mt_rand(),
                12.5467678,
                -202.5467678,
                [],
                [],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                null,
                true,
                null,
                1024,
                null
            ],
        ];
    }


    /**
     * @return array
     */
    public function provideValidConstructorParameters(): array
    {
        $image = new EntityImage('url');
        return [
            [
                'title-'.mt_rand(),
                'content'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc'.mt_rand(),
                12.5467678,
                -22.5467678,
                [1, 2, 3],
                [$image],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                null,
                true,
                null,
                null,
                null
            ],
            [
                'title-'.mt_rand(),
                'content'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc'.mt_rand(),
                12.5467678,
                -22.5467678,
                [1, 2, 3],
                [$image],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                EventConsumerFlagEnum::CITIZEN,
                true,
                null,
                null,
                null
            ],
            [
                'title-'.mt_rand(),
                'content'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc'.mt_rand(),
                12.5467678,
                -22.5467678,
                [1, 2, 3],
                [$image],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                EventConsumerFlagEnum::CITIZEN,
                true,
                EventApproveStateEnum::WAITING_FOR_DELETE,
                null,
                null
            ],
            [
                'title-'.mt_rand(),
                'content'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc'.mt_rand(),
                12.5467678,
                -22.5467678,
                [1, 2, 3],
                [$image],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                null,
                true,
                null,
                EventFeaturedLevelEnum::EDITORIAL_TIP,
                null
            ],
        ];
    }
}