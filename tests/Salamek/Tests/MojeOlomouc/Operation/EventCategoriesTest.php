<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Model\EventCategory;
use Salamek\MojeOlomouc\Model\IEventCategory;
use Salamek\MojeOlomouc\Operation\EventCategories;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

class EventCategoriesTest extends BaseTest
{
    /**
     * @test
     * @dataProvider provideGetAllConstructorParameters
     * @param \DateTimeInterface|null $fromUpdatedAt
     * @param bool $showDeleted
     * @param bool $onlyVisible
     * @param bool $extraFields
     */
    public function getAllShouldBeGoodTest(\DateTimeInterface $fromUpdatedAt = null, bool $showDeleted = false, bool $onlyVisible = true, bool $extraFields = false): void
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

        $eventCategories = new EventCategories($request);
        $response = $eventCategories->getAll(
            $fromUpdatedAt,
            $showDeleted,
            $onlyVisible,
            $extraFields
        );

        $this->assertInternalType('array', $catchRequestInfo['query']);
        $this->assertArrayHasKey('fromUpdatedAt', $catchRequestInfo['query']);
        $this->assertArrayHasKey('showDeleted', $catchRequestInfo['query']);
        $this->assertArrayHasKey('onlyVisible', $catchRequestInfo['query']);
        $this->assertArrayHasKey('extraFields', $catchRequestInfo['query']);
        $this->assertEquals(($fromUpdatedAt ? $fromUpdatedAt->format(DateTime::NOT_A_ISO8601) : null), $catchRequestInfo['query']['fromUpdatedAt']);
        $this->assertEquals($showDeleted, $catchRequestInfo['query']['showDeleted']);
        $this->assertEquals($onlyVisible, $catchRequestInfo['query']['onlyVisible']);
        $this->assertEquals($extraFields, $catchRequestInfo['query']['extraFields']);
        $this->assertEquals('GET', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/export/event-categories', $catchUri);
        $this->assertInstanceOf(Response::class, $response);
    }


    /**
     * @test
     * @dataProvider provideCreateConstructorParameters
     * @param IEventCategory $eventCategory
     */
    public function createShouldBeGoodTest(IEventCategory $eventCategory)
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
        $eventCategories = new EventCategories($request);
        $response = $eventCategories->create([$eventCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('eventCategory', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveEventCategory = $primitivePayloadItem['eventCategory'];
        $primitiveAction = $primitivePayloadItem['action'];
        
        $this->assertInternalType('array', $primitiveEventCategory);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('title', $primitiveEventCategory);
        if (!is_null($eventCategory->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveEventCategory);

        $this->assertEquals($eventCategory->getTitle(), $primitiveEventCategory['title']);
        if (!is_null($eventCategory->getIsVisible())) $this->assertEquals($eventCategory->getIsVisible(), $primitiveEventCategory['isVisible']);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/event-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $primitiveAction['code']);
        $this->assertEquals($eventCategory->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideUpdateConstructorParameters
     * @param IEventCategory $eventCategory
     */
    public function updateShouldBeGoodTest(IEventCategory $eventCategory)
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
        $eventCategories = new EventCategories($request);
        $response = $eventCategories->update([$eventCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('eventCategory', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveEventCategory = $primitivePayloadItem['eventCategory'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveEventCategory);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('title', $primitiveEventCategory);
        if (!is_null($eventCategory->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveEventCategory);

        $this->assertEquals($eventCategory->getTitle(), $primitiveEventCategory['title']);
        if (!is_null($eventCategory->getIsVisible())) $this->assertEquals($eventCategory->getIsVisible(), $primitiveEventCategory['isVisible']);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/event-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $primitiveAction['code']);
        $this->assertEquals($eventCategory->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideValidDeleteConstructorParameters
     * @param IEventCategory $eventCategory
     */
    public function deleteRequestShouldBeGoodTest(IEventCategory $eventCategory = null)
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
        $eventCategories = new EventCategories($request);
        $response = $eventCategories->delete([$eventCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];
        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveAction);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/event-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $primitiveAction['code']);
        $this->assertEquals($eventCategory->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideInvalidDeleteConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param IEventCategory $eventCategory
     */
    public function deleteRequestShouldFailTest(IEventCategory $eventCategory)
    {
        $apiKey = $this->getTestApiKey();

        $client = $this->getClientMock();

        $request = new Request($client, $apiKey);
        $eventCategories = new EventCategories($request);
        $eventCategories->delete([$eventCategory]);
    }

    /**
     * @return array
     */
    public function provideInvalidDeleteConstructorParameters(): array
    {
        return [
            [new EventCategory(
                'title-'.mt_rand(),
                true,
                null
            )]
        ];
    }

    /**
     * @return array
     */
    public function provideValidDeleteConstructorParameters(): array
    {
        return [
            [new EventCategory(
                'title-'.mt_rand(),
                true,
                mt_rand()
            )]
        ];
    }

    /**
     * @return array
     */
    public function provideUpdateConstructorParameters(): array
    {
        return [
            [new EventCategory(
                'title-'.mt_rand(),
                true,
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
            [new EventCategory(
                'title-'.mt_rand()
            )],
            [new EventCategory(
                'title-'.mt_rand(),
                false,
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
                false,
            ],
            [
                $this->getDateTime(),
                false,
                true,
                false,
            ],
            [
                null,
                true,
                true,
                false,
            ],
            [
                null,
                false,
                false,
                false,
            ],
            [
                null,
                false,
                true,
                false,
            ],
            [
                null,
                false,
                true,
                true,
            ]
        ];
    }
}