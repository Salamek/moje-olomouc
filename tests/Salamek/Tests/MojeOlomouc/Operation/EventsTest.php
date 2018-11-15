<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Enum\ArticleApproveStateEnum;
use Salamek\MojeOlomouc\Enum\DateTime;
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
        $this->assertEquals(($fromUpdatedAt ? $fromUpdatedAt->format(DateTime::NOT_A_ISO8601) : null), $catchRequestInfo['query']['fromUpdatedAt']);
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

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('event', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveEvent = $primitivePayloadItem['event'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveEvent);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('title', $primitiveEvent);
        $this->assertArrayHasKey('description', $primitiveEvent);
        $this->assertArrayHasKey('startAt', $primitiveEvent);
        $this->assertArrayHasKey('endAt', $primitiveEvent);
        $this->assertArrayHasKey('placeDesc', $primitiveEvent);
        $this->assertArrayHasKey('placeLat', $primitiveEvent);
        $this->assertArrayHasKey('placeLon', $primitiveEvent);
        $this->assertArrayHasKey('categoryIdsArr', $primitiveEvent);
        $this->assertArrayHasKey('images', $primitiveEvent);
        if (!is_null($event->getAttachmentUrl())) $this->assertArrayHasKey('attachmentUrl', $primitiveEvent);
        if (!is_null($event->getFee())) $this->assertArrayHasKey('fee', $primitiveEvent);
        if (!is_null($event->getWebUrl())) $this->assertArrayHasKey('webUrl', $primitiveEvent);
        if (!is_null($event->getFacebookUrl())) $this->assertArrayHasKey('facebookUrl', $primitiveEvent);
        if (!is_null($event->getConsumerFlags())) $this->assertArrayHasKey('consumerFlags', $primitiveEvent);
        if (!is_null($event->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveEvent);
        if (!is_null($event->getApproveState())) $this->assertArrayHasKey('approveState', $primitiveEvent);
        if (!is_null($event->getFeaturedLevel())) $this->assertArrayHasKey('featuredLevel', $primitiveEvent);
        $this->assertEquals($event->getTitle(), $primitiveEvent['title']);
        $this->assertEquals($event->getDescription(), $primitiveEvent['description']);
        $this->assertEquals($event->getStartAt()->format(DateTime::NOT_A_ISO8601), $primitiveEvent['startAt']);
        $this->assertEquals($event->getEndAt()->format(DateTime::NOT_A_ISO8601), $primitiveEvent['endAt']);
        $this->assertEquals($event->getPlaceDesc(), $primitiveEvent['placeDesc']);
        $this->assertEquals($event->getPlaceLat(), $primitiveEvent['placeLat']);
        $this->assertEquals($event->getPlaceLon(), $primitiveEvent['placeLon']);
        $this->assertEquals($event->getCategoryIdsArr(), $primitiveEvent['categoryIdsArr']);
        $this->assertEquals($primitiveImages, $primitiveEvent['images']);
        if (!is_null($event->getAttachmentUrl())) $this->assertEquals($event->getAttachmentUrl(), $primitiveEvent['attachmentUrl']);
        if (!is_null($event->getFee())) $this->assertEquals($event->getFee(), $primitiveEvent['fee']);
        if (!is_null($event->getWebUrl())) $this->assertEquals($event->getWebUrl(), $primitiveEvent['webUrl']);
        if (!is_null($event->getFacebookUrl())) $this->assertEquals($event->getFacebookUrl(), $primitiveEvent['facebookUrl']);
        if (!is_null($event->getConsumerFlags())) $this->assertEquals($event->getConsumerFlags(), $primitiveEvent['consumerFlags']);
        if (!is_null($event->getIsVisible())) $this->assertEquals($event->getIsVisible(), $primitiveEvent['isVisible']);
        if (!is_null($event->getApproveState())) $this->assertEquals($event->getApproveState(), $primitiveEvent['approveState']);
        if (!is_null($event->getFeaturedLevel())) $this->assertEquals($event->getFeaturedLevel(), $primitiveEvent['featuredLevel']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/events', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $primitiveAction['code']);
        $this->assertEquals(null, $primitiveAction['id']);
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

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('event', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveEvent = $primitivePayloadItem['event'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveEvent);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('title', $primitiveEvent);
        $this->assertArrayHasKey('description', $primitiveEvent);
        $this->assertArrayHasKey('startAt', $primitiveEvent);
        $this->assertArrayHasKey('endAt', $primitiveEvent);
        $this->assertArrayHasKey('placeDesc', $primitiveEvent);
        $this->assertArrayHasKey('placeLat', $primitiveEvent);
        $this->assertArrayHasKey('placeLon', $primitiveEvent);
        $this->assertArrayHasKey('categoryIdsArr', $primitiveEvent);
        $this->assertArrayHasKey('images', $primitiveEvent);
        if (!is_null($event->getAttachmentUrl())) $this->assertArrayHasKey('attachmentUrl', $primitiveEvent);
        if (!is_null($event->getFee())) $this->assertArrayHasKey('fee', $primitiveEvent);
        if (!is_null($event->getWebUrl())) $this->assertArrayHasKey('webUrl', $primitiveEvent);
        if (!is_null($event->getFacebookUrl())) $this->assertArrayHasKey('facebookUrl', $primitiveEvent);
        if (!is_null($event->getConsumerFlags())) $this->assertArrayHasKey('consumerFlags', $primitiveEvent);
        if (!is_null($event->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveEvent);
        if (!is_null($event->getApproveState())) $this->assertArrayHasKey('approveState', $primitiveEvent);
        if (!is_null($event->getFeaturedLevel())) $this->assertArrayHasKey('featuredLevel', $primitiveEvent);
        $this->assertEquals($event->getTitle(), $primitiveEvent['title']);
        $this->assertEquals($event->getDescription(), $primitiveEvent['description']);
        $this->assertEquals($event->getStartAt()->format(DateTime::NOT_A_ISO8601), $primitiveEvent['startAt']);
        $this->assertEquals($event->getEndAt()->format(DateTime::NOT_A_ISO8601), $primitiveEvent['endAt']);
        $this->assertEquals($event->getPlaceDesc(), $primitiveEvent['placeDesc']);
        $this->assertEquals($event->getPlaceLat(), $primitiveEvent['placeLat']);
        $this->assertEquals($event->getPlaceLon(), $primitiveEvent['placeLon']);
        $this->assertEquals($event->getCategoryIdsArr(), $primitiveEvent['categoryIdsArr']);
        $this->assertEquals($primitiveImages, $primitiveEvent['images']);
        if (!is_null($event->getAttachmentUrl())) $this->assertEquals($event->getAttachmentUrl(), $primitiveEvent['attachmentUrl']);
        if (!is_null($event->getFee())) $this->assertEquals($event->getFee(), $primitiveEvent['fee']);
        if (!is_null($event->getWebUrl())) $this->assertEquals($event->getWebUrl(), $primitiveEvent['webUrl']);
        if (!is_null($event->getFacebookUrl())) $this->assertEquals($event->getFacebookUrl(), $primitiveEvent['facebookUrl']);
        if (!is_null($event->getConsumerFlags())) $this->assertEquals($event->getConsumerFlags(), $primitiveEvent['consumerFlags']);
        if (!is_null($event->getIsVisible())) $this->assertEquals($event->getIsVisible(), $primitiveEvent['isVisible']);
        if (!is_null($event->getApproveState())) $this->assertEquals($event->getApproveState(), $primitiveEvent['approveState']);
        if (!is_null($event->getFeaturedLevel())) $this->assertEquals($event->getFeaturedLevel(), $primitiveEvent['featuredLevel']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/events', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $primitiveAction['code']);
        $this->assertEquals((is_null($id) ? $event->getId() : $id), $primitiveAction['id']);
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

        $primitivePayloadItem = $catchRequestInfo['json'][0];
        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveAction);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/events', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $primitiveAction['code']);
        $this->assertEquals((is_null($id) ? $event->getId() : $id), $primitiveAction['id']);
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
                $this->getDateTime(),
                $this->getDateTime(),
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
                $this->getDateTime(),
                $this->getDateTime(),
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
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                [1, 2, 3, 4]
            ), mt_rand()],
            [new Event(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
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
                $this->getDateTime(),
                $this->getDateTime(),
                'placeDesc-'.mt_rand(),
                '12.'.mt_rand(),
                '-12.'.mt_rand(),
                [1, 2, 3, 4]
            )],
            [new Event(
                'title-'.mt_rand(),
                'description-'.mt_rand(),
                $this->getDateTime(),
                $this->getDateTime(),
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
                $this->getDateTime(),
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