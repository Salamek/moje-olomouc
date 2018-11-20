<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\EventCategorySourceEnum;
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
     * @param \DateTimeInterface|null $from
     * @param bool $deleted
     * @param bool $invisible
     * @param bool $withExtraFields
     * @param string $source
     */
    public function getAllShouldBeGoodTest(
        \DateTimeInterface $from = null,
        bool $deleted = false,
        bool $invisible = false,
        bool $withExtraFields = false,
        string $source = EventCategorySourceEnum::PUBLISHED
    ): void
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
            $from,
            $deleted,
            $invisible,
            $withExtraFields,
            $source
        );

        $this->assertInternalType('array', $catchRequestInfo['query']);
        $this->assertArrayHasKey('from', $catchRequestInfo['query']);
        $this->assertArrayHasKey('deleted', $catchRequestInfo['query']);
        $this->assertArrayHasKey('invisible', $catchRequestInfo['query']);
        $this->assertArrayHasKey('withExtraFields', $catchRequestInfo['query']);
        $this->assertArrayHasKey('source', $catchRequestInfo['query']);
        $this->assertEquals(($from ? $from->format(DateTime::A_ISO8601) : null), $catchRequestInfo['query']['from']);
        $this->assertEquals($this->boolToString($deleted), $catchRequestInfo['query']['deleted']);
        $this->assertEquals($this->boolToString($invisible), $catchRequestInfo['query']['invisible']);
        $this->assertEquals($this->boolToString($withExtraFields), $catchRequestInfo['query']['withExtraFields']);
        $this->assertEquals($source, $catchRequestInfo['query']['source']);
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