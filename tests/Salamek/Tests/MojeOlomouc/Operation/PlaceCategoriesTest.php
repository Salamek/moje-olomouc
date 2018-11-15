<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\PlaceCategoryConsumerFlagEnum;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Model\PlaceCategory;
use Salamek\MojeOlomouc\Model\IPlaceCategory;
use Salamek\MojeOlomouc\Operation\PlaceCategories;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;

class PlaceCategoriesTest extends BaseTest
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

        $articleCategories = new PlaceCategories($request);
        $response = $articleCategories->getAll(
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
        $this->assertEquals('/api/export/place-categories', $catchUri);
        $this->assertInstanceOf(Response::class, $response);
    }


    /**
     * @test
     * @dataProvider provideCreateConstructorParameters
     * @param IPlaceCategory $placeCategory
     */
    public function createShouldBeGoodTest(IPlaceCategory $placeCategory)
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
        $placeCategories = new PlaceCategories($request);
        $response = $placeCategories->create([$placeCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('placeCategory', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitivePlaceCategory = $primitivePayloadItem['placeCategory'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitivePlaceCategory);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('title', $primitivePlaceCategory);
        if (!is_null($placeCategory->getConsumerFlags())) $this->assertArrayHasKey('consumerFlags', $primitivePlaceCategory);
        if (!is_null($placeCategory->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitivePlaceCategory);

        $this->assertEquals($placeCategory->getTitle(), $primitivePlaceCategory['title']);
        if (!is_null($placeCategory->getConsumerFlags())) $this->assertEquals($placeCategory->getConsumerFlags(), $primitivePlaceCategory['consumerFlags']);
        if (!is_null($placeCategory->getIsVisible())) $this->assertEquals($placeCategory->getIsVisible(), $primitivePlaceCategory['isVisible']);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/place-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $primitiveAction['code']);
        $this->assertEquals($placeCategory->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideUpdateConstructorParameters
     * @param IPlaceCategory $placeCategory
     */
    public function updateShouldBeGoodTest(IPlaceCategory $placeCategory)
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
        $placeCategories = new PlaceCategories($request);
        $response = $placeCategories->update([$placeCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('placeCategory', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitivePlaceCategory = $primitivePayloadItem['placeCategory'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitivePlaceCategory);
        $this->assertInternalType('array', $primitiveAction);
        $this->assertArrayHasKey('title', $primitivePlaceCategory);
        if (!is_null($placeCategory->getConsumerFlags())) $this->assertArrayHasKey('consumerFlags', $primitivePlaceCategory);
        if (!is_null($placeCategory->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitivePlaceCategory);

        $this->assertEquals($placeCategory->getTitle(), $primitivePlaceCategory['title']);
        if (!is_null($placeCategory->getConsumerFlags())) $this->assertEquals($placeCategory->getConsumerFlags(), $primitivePlaceCategory['consumerFlags']);
        if (!is_null($placeCategory->getIsVisible())) $this->assertEquals($placeCategory->getIsVisible(), $primitivePlaceCategory['isVisible']);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/place-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $primitiveAction['code']);
        $this->assertEquals($placeCategory->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideValidDeleteConstructorParameters
     * @param IPlaceCategory $placeCategory
     */
    public function deleteRequestShouldBeGoodTest(IPlaceCategory $placeCategory)
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
        $placeCategories = new PlaceCategories($request);
        $response = $placeCategories->delete([$placeCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];
        $this->assertInternalType('array', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertInternalType('array', $primitiveAction);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/place-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $primitiveAction['code']);
        $this->assertEquals($placeCategory->getId(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @test
     * @dataProvider provideInvalidDeleteConstructorParameters
     * @expectedException Salamek\MojeOlomouc\Exception\InvalidArgumentException
     * @param IPlaceCategory $placeCategory
     */
    public function deleteRequestShouldFailTest(IPlaceCategory $placeCategory)
    {
        $apiKey = $this->getTestApiKey();

        $client = $this->getClientMock();

        $request = new Request($client, $apiKey);
        $placeCategories = new PlaceCategories($request);
        $placeCategories->delete([$placeCategory]);
    }

    /**
     * @return array
     */
    public function provideInvalidDeleteConstructorParameters(): array
    {
        return [
            [new PlaceCategory(
                'title-'.mt_rand(),
                null,
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
            [new PlaceCategory(
                'title-'.mt_rand(),
                null,
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
            [new PlaceCategory(
                'title-'.mt_rand(),
                null,
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
            [new PlaceCategory(
                'title-'.mt_rand()
            )],
            [new PlaceCategory(
                'title-'.mt_rand(),
                PlaceCategoryConsumerFlagEnum::STUDENT,
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