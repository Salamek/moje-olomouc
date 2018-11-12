<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

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
        $this->assertEquals(($fromUpdatedAt ? $fromUpdatedAt->format(\DateTime::ISO8601) : null), $catchRequestInfo['query']['fromUpdatedAt']);
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
        $response = $eventCategories->create($eventCategory);

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertArrayHasKey('eventCategory', $catchRequestInfo['json']);
        $this->assertArrayHasKey('title', $catchRequestInfo['json']['eventCategory']);
        $this->assertArrayHasKey('isVisible', $catchRequestInfo['json']['eventCategory']);

        $this->assertEquals($eventCategory->getTitle(), $catchRequestInfo['json']['eventCategory']['title']);
        $this->assertEquals($eventCategory->getIsVisible(), $catchRequestInfo['json']['eventCategory']['isVisible']);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/event-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals(null, $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideUpdateConstructorParameters
     * @param IEventCategory $eventCategory
     * @param int|null $id
     */
    public function updateShouldBeGoodTest(IEventCategory $eventCategory, int $id = null)
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
        $response = $eventCategories->update($eventCategory, $id);

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertArrayHasKey('eventCategory', $catchRequestInfo['json']);
        $this->assertArrayHasKey('title', $catchRequestInfo['json']['eventCategory']);
        $this->assertArrayHasKey('isVisible', $catchRequestInfo['json']['eventCategory']);

        $this->assertEquals($eventCategory->getTitle(), $catchRequestInfo['json']['eventCategory']['title']);
        $this->assertEquals($eventCategory->getIsVisible(), $catchRequestInfo['json']['eventCategory']['isVisible']);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/event-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals((is_null($id) ? $eventCategory->getId() : $id), $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideValidDeleteConstructorParameters
     * @param IEventCategory|null $eventCategory
     * @param int|null $id
     */
    public function deleteRequestShouldBeGoodTest(IEventCategory $eventCategory = null, int $id = null)
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
        $response = $eventCategories->delete($eventCategory, $id);

        $this->assertInternalType('array', $catchRequestInfo['json']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/event-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $catchRequestInfo['json']['action']['code']);
        $this->assertEquals((is_null($id) ? $eventCategory->getId() : $id), $catchRequestInfo['json']['action']['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideInvalidDeleteConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param IEventCategory|null $eventCategory
     * @param int|null $id
     */
    public function deleteRequestShouldFailTest(IEventCategory $eventCategory = null, int $id = null)
    {
        $apiKey = $this->getTestApiKey();

        $client = $this->getClientMock();

        $request = new Request($client, $apiKey);
        $eventCategories = new EventCategories($request);
        $eventCategories->delete($eventCategory, $id);
    }

    /**
     * @return array
     */
    public function provideInvalidDeleteConstructorParameters(): array
    {
        return [
            [null, null],
            [new EventCategory(
                'title-'.mt_rand(),
                true,
                null
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
            [new EventCategory(
                'title-'.mt_rand(),
                true,
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
            [new EventCategory(
                'title-'.mt_rand(),
                true,
                null
            ), mt_rand()],
            [new EventCategory(
                'title-'.mt_rand(),
                true,
                mt_rand()
            ), null],
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
                new \DateTime(),
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