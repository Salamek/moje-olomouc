<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Enum\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Enum\EventApproveStateEnum;
use Salamek\MojeOlomouc\Enum\EventConsumerFlagEnum;
use Salamek\MojeOlomouc\Enum\EventFeaturedLevelEnum;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Model\Event;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\IEvent;
use Salamek\MojeOlomouc\Operation\Events;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

class EventsTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideGetAllConstructorParameters
     * @param \DateTimeInterface|null $fromUpdatedAt
     * @param bool $showDeleted
     * @param bool $onlyApproved
     * @param bool $onlyVisible
     * @param bool $extraFields
     */
    public function getAllShouldBeGoodTest(\DateTimeInterface $fromUpdatedAt = null, bool $showDeleted = false, bool $onlyApproved = true, bool $onlyVisible = true, bool $extraFields = false): void
    {
        $apiKey = $this->getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->will($this->returnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            }));


        $request = new Request($client, $apiKey);

        $article = new Events($request);
        $response = $article->getAll(
            $fromUpdatedAt,
            $showDeleted,
            $onlyApproved,
            $onlyVisible,
            $extraFields
        );

        $this->assertInternalType('array', $catchRequestInfo['query']);
        $this->assertArrayHasKey('fromUpdatedAt', $catchRequestInfo['query']);
        $this->assertArrayHasKey('showDeleted', $catchRequestInfo['query']);
        $this->assertArrayHasKey('onlyApproved', $catchRequestInfo['query']);
        $this->assertArrayHasKey('onlyVisible', $catchRequestInfo['query']);
        $this->assertArrayHasKey('extraFields', $catchRequestInfo['query']);
        $this->assertEquals(($fromUpdatedAt ? $fromUpdatedAt->format(\DateTime::ISO8601) : null), $catchRequestInfo['query']['fromUpdatedAt']);
        $this->assertEquals($showDeleted, $catchRequestInfo['query']['showDeleted']);
        $this->assertEquals($onlyApproved, $catchRequestInfo['query']['onlyApproved']);
        $this->assertEquals($onlyVisible, $catchRequestInfo['query']['onlyVisible']);
        $this->assertEquals($extraFields, $catchRequestInfo['query']['extraFields']);
        $this->assertEquals('GET', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/export/events', $catchUri);
        $this->assertInstanceOf(Response::class, $response);
    }


    /**
     * @test
     * @dataProvider provideCreateConstructorParameters
     * @param IEvent $event
     */
    public function createShouldBeGoodTest(IEvent $event)
    {
        $apiKey = $this->getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->will($this->returnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            }));

        $request = new Request($client, $apiKey);
        $events = new Events($request);
        $response = $events->create($event);

        $primitiveImages = [];
        foreach ($event->getImages() AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertArrayHasKey('event', $catchRequestInfo['json']);
        $this->assertArrayHasKey('title', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('description', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('startAt', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('endAt', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('placeDesc', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('placeLat', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('placeLon', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('categoryIdsArr', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('images', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('attachmentUrl', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('fee', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('webUrl', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('facebookUrl', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('consumerFlags', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('isVisible', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('approveState', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('featuredLevel', $catchRequestInfo['json']['event']);
        $this->assertEquals($event->getTitle(), $catchRequestInfo['json']['event']['title']);
        $this->assertEquals($event->getDescription(), $catchRequestInfo['json']['event']['description']);
        $this->assertEquals($event->getStartAt()->format(\DateTime::ISO8601), $catchRequestInfo['json']['event']['startAt']);
        $this->assertEquals($event->getEndAt()->format(\DateTime::ISO8601), $catchRequestInfo['json']['event']['endAt']);
        $this->assertEquals($event->getPlaceDesc(), $catchRequestInfo['json']['event']['placeDesc']);
        $this->assertEquals($event->getPlaceLat(), $catchRequestInfo['json']['event']['placeLat']);
        $this->assertEquals($event->getPlaceLon(), $catchRequestInfo['json']['event']['placeLon']);
        $this->assertEquals($event->getCategoryIdsArr(), $catchRequestInfo['json']['event']['categoryIdsArr']);
        $this->assertEquals($primitiveImages, $catchRequestInfo['json']['event']['images']);
        $this->assertEquals($event->getAttachmentUrl(), $catchRequestInfo['json']['event']['attachmentUrl']);
        $this->assertEquals($event->getFee(), $catchRequestInfo['json']['event']['fee']);
        $this->assertEquals($event->getWebUrl(), $catchRequestInfo['json']['event']['webUrl']);
        $this->assertEquals($event->getFacebookUrl(), $catchRequestInfo['json']['event']['facebookUrl']);
        $this->assertEquals($event->getConsumerFlags(), $catchRequestInfo['json']['event']['consumerFlags']);
        $this->assertEquals($event->getIsVisible(), $catchRequestInfo['json']['event']['isVisible']);
        $this->assertEquals($event->getApproveState(), $catchRequestInfo['json']['event']['approveState']);
        $this->assertEquals($event->getFeaturedLevel(), $catchRequestInfo['json']['event']['featuredLevel']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/events', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals(null, $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideUpdateConstructorParameters
     * @param IEvent $event
     * @param int|null $id
     */
    public function updateShouldBeGoodTest(IEvent $event, int $id = null)
    {
        $apiKey = $this->getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->will($this->returnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            }));

        $request = new Request($client, $apiKey);
        $events = new Events($request);
        $response = $events->update($event, $id);

        $primitiveImages = [];
        foreach ($event->getImages() AS $image)
        {
            $primitiveImages[] = $image->toPrimitiveArray();
        }

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertArrayHasKey('event', $catchRequestInfo['json']);
        $this->assertArrayHasKey('title', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('description', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('startAt', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('endAt', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('placeDesc', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('placeLat', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('placeLon', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('categoryIdsArr', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('images', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('attachmentUrl', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('fee', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('webUrl', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('facebookUrl', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('consumerFlags', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('isVisible', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('approveState', $catchRequestInfo['json']['event']);
        $this->assertArrayHasKey('featuredLevel', $catchRequestInfo['json']['event']);
        $this->assertEquals($event->getTitle(), $catchRequestInfo['json']['event']['title']);
        $this->assertEquals($event->getDescription(), $catchRequestInfo['json']['event']['description']);
        $this->assertEquals($event->getStartAt()->format(\DateTime::ISO8601), $catchRequestInfo['json']['event']['startAt']);
        $this->assertEquals($event->getEndAt()->format(\DateTime::ISO8601), $catchRequestInfo['json']['event']['endAt']);
        $this->assertEquals($event->getPlaceDesc(), $catchRequestInfo['json']['event']['placeDesc']);
        $this->assertEquals($event->getPlaceLat(), $catchRequestInfo['json']['event']['placeLat']);
        $this->assertEquals($event->getPlaceLon(), $catchRequestInfo['json']['event']['placeLon']);
        $this->assertEquals($event->getCategoryIdsArr(), $catchRequestInfo['json']['event']['categoryIdsArr']);
        $this->assertEquals($primitiveImages, $catchRequestInfo['json']['event']['images']);
        $this->assertEquals($event->getAttachmentUrl(), $catchRequestInfo['json']['event']['attachmentUrl']);
        $this->assertEquals($event->getFee(), $catchRequestInfo['json']['event']['fee']);
        $this->assertEquals($event->getWebUrl(), $catchRequestInfo['json']['event']['webUrl']);
        $this->assertEquals($event->getFacebookUrl(), $catchRequestInfo['json']['event']['facebookUrl']);
        $this->assertEquals($event->getConsumerFlags(), $catchRequestInfo['json']['event']['consumerFlags']);
        $this->assertEquals($event->getIsVisible(), $catchRequestInfo['json']['event']['isVisible']);
        $this->assertEquals($event->getApproveState(), $catchRequestInfo['json']['event']['approveState']);
        $this->assertEquals($event->getFeaturedLevel(), $catchRequestInfo['json']['event']['featuredLevel']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/events', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals((is_null($id) ? $event->getId() : $id), $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideValidDeleteConstructorParameters
     * @param IEvent|null $event
     * @param int|null $id
     */
    public function deleteRequestShouldBeGoodTest(IEvent $event = null, int $id = null)
    {
        $apiKey = $this->getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->will($this->returnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            }));

        $request = new Request($client, $apiKey);
        $events = new Events($request);
        $response = $events->delete($event, $id);

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/events', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals((is_null($id) ? $event->getId() : $id), $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideInvalidDeleteConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param IEvent|null $event
     * @param int|null $id
     */
    public function deleteRequestShouldFailTest(IEvent $event = null, int $id = null)
    {
        $apiKey = $this->getTestApiKey();

        $client = $this->getClientMock();

        $request = new Request($client, $apiKey);
        $events = new Events($request);
        $events->delete($event, $id);
    }

    /**
     * @return array
     */
    public function provideInvalidDeleteConstructorParameters(): array
    {
        return [
            [null, null],
            [new Event(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                new \DateTime(),
                new \DateTime(),
                'placeDesc-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                [1, 2, 3, 4]
            ), null]
        ];
    }

    /**
     * @return array
     */
    public function provideValidDeleteConstructorParameters(): array
    {
        return [
            [null, mt_rand()],
            [new Event(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                new \DateTime(),
                new \DateTime(),
                'placeDesc-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                [1, 2, 3, 4],
                [new EntityImage('url-'.mt_rand())],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                EventConsumerFlagEnum::CITIZEN | EventConsumerFlagEnum::STUDENT,
                false,
                EventApproveStateEnum::APPROVED,
                EventFeaturedLevelEnum::EDITORIAL_TIP,
                mt_rand()
            ), null]
        ];
    }

    /**
     * @return array
     */
    public function provideUpdateConstructorParameters(): array
    {
        return [
            [new Event(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                new \DateTime(),
                new \DateTime(),
                'placeDesc-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                [1, 2, 3, 4]
            ), mt_rand()],
            [new Event(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                new \DateTime(),
                new \DateTime(),
                'placeDesc-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                [1, 2, 3, 4],
                [new EntityImage('url-'.mt_rand())],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                EventConsumerFlagEnum::CITIZEN | EventConsumerFlagEnum::STUDENT,
                false,
                EventApproveStateEnum::APPROVED,
                EventFeaturedLevelEnum::EDITORIAL_TIP,
                mt_rand()
            )],
        ];
    }

    /**
     * @return array
     */
    public function provideCreateConstructorParameters(): array
    {
        return [
            [new Event(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                new \DateTime(),
                new \DateTime(),
                'placeDesc-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                [1, 2, 3, 4]
            )],
            [new Event(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                new \DateTime(),
                new \DateTime(),
                'placeDesc-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                [1, 2, 3, 4],
                [new EntityImage('url-'.mt_rand())],
                'attachmentUrl-'.mt_rand(),
                'fee-'.mt_rand(),
                'webUrl-'.mt_rand(),
                'facebookUrl-'.mt_rand(),
                EventConsumerFlagEnum::CITIZEN | EventConsumerFlagEnum::STUDENT,
                false,
                EventApproveStateEnum::APPROVED,
                EventFeaturedLevelEnum::EDITORIAL_TIP,
                mt_rand()
            )],
        ];
    }

    /**
     * @return array
     */
    public function provideGetAllConstructorParameters(): array
    {
        return [
            [
                null,
                false,
                true,
                true,
                false,
            ],
            [
                new \DateTime(),
                false,
                true,
                true,
                false,
            ],
            [
                null,
                true,
                true,
                true,
                false,
            ],
            [
                null,
                false,
                false,
                true,
                false,
            ],
            [
                null,
                false,
                true,
                false,
                false,
            ],
            [
                null,
                false,
                true,
                true,
                true,
            ]
        ];
    }
}