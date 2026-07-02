<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Enum\ArticleCategorySourceEnum;
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
    private $hydrator;


    public function setUp(): void
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(\Salamek\MojeOlomouc\Hydrator\IPlaceCategory::class);
    }

    /**
     * @param \DateTimeInterface|null $from
     * @param bool $deleted
     * @param bool $invisible
     * @param bool $withExtraFields
     * @param string $source
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideGetAllConstructorParameters')]

    public function getAllShouldBeGoodTest(
        ?\DateTimeInterface $from = null,
        bool $deleted = false,
        bool $invisible = false,
        bool $withExtraFields = false,
        string $source = ArticleCategorySourceEnum::PUBLISHED
    ): void
    {
        $apiKey = self::getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->willReturnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            });


        $request = new Request($client, $apiKey);

        $articleCategories = new PlaceCategories($request, $this->hydrator);
        $response = $articleCategories->getAll(
            $from,
            $deleted,
            $invisible,
            $withExtraFields,
            $source
        );

        $this->assertIsArray($catchRequestInfo['query']);
        $this->assertArrayHasKey('from', $catchRequestInfo['query']);
        $this->assertArrayHasKey('deleted', $catchRequestInfo['query']);
        $this->assertArrayHasKey('invisible', $catchRequestInfo['query']);
        $this->assertArrayHasKey('withExtraFields', $catchRequestInfo['query']);
        $this->assertArrayHasKey('source', $catchRequestInfo['query']);
        $this->assertEquals(($from ? $from->format(DateTime::A_ISO8601) : null), $catchRequestInfo['query']['from']);
        $this->assertEquals(self::boolToString($deleted), $catchRequestInfo['query']['deleted']);
        $this->assertEquals(self::boolToString($invisible), $catchRequestInfo['query']['invisible']);
        $this->assertEquals(self::boolToString($withExtraFields), $catchRequestInfo['query']['withExtraFields']);
        $this->assertEquals($source, $catchRequestInfo['query']['source']);
        $this->assertEquals('GET', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/export/place-categories', $catchUri);
        $this->assertInstanceOf(Response::class, $response);
    }


    /**
     * @param IPlaceCategory $placeCategory
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideCreateConstructorParameters')]

    public function createShouldBeGoodTest(IPlaceCategory $placeCategory)
    {
        $apiKey = self::getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->willReturnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            });

        $request = new Request($client, $apiKey);
        $placeCategories = new PlaceCategories($request, $this->hydrator);
        $response = $placeCategories->create([$placeCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertIsArray($primitivePayloadItem);
        $this->assertArrayHasKey('placeCategory', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitivePlaceCategory = $primitivePayloadItem['placeCategory'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertIsArray($primitivePlaceCategory);
        $this->assertIsArray($primitiveAction);
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
        $this->assertEquals($placeCategory->getEntityIdentifier(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @param IPlaceCategory $placeCategory
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideUpdateConstructorParameters')]

    public function updateShouldBeGoodTest(IPlaceCategory $placeCategory)
    {
        $apiKey = self::getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->willReturnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            });

        $request = new Request($client, $apiKey);
        $placeCategories = new PlaceCategories($request, $this->hydrator);
        $response = $placeCategories->update([$placeCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertIsArray($primitivePayloadItem);
        $this->assertArrayHasKey('placeCategory', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitivePlaceCategory = $primitivePayloadItem['placeCategory'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertIsArray($primitivePlaceCategory);
        $this->assertIsArray($primitiveAction);
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
        $this->assertEquals($placeCategory->getEntityIdentifier(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @param IPlaceCategory $placeCategory
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideValidDeleteConstructorParameters')]

    public function deleteRequestShouldBeGoodTest(IPlaceCategory $placeCategory)
    {
        $apiKey = self::getTestApiKey();

        $catchType = null;
        $catchUri = null;
        $catchRequestInfo = null;
        $responseData = $this->getFakeResponseData();
        $responseDataString = json_encode($responseData);

        $response = $this->getResponseMockWithBody($responseDataString);

        $client = $this->getClientMock();
        $client->expects($this->once())
            ->method('request')
            ->willReturnCallback(function ($type, $uri, $requestInfo) use (&$catchRequestInfo, &$catchType, &$catchUri, $response) {
                $catchType = $type;
                $catchUri = $uri;
                $catchRequestInfo = $requestInfo;
                return $response;
            });

        $request = new Request($client, $apiKey);
        $placeCategories = new PlaceCategories($request, $this->hydrator);
        $response = $placeCategories->delete([$placeCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];
        $this->assertIsArray($primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertIsArray($primitiveAction);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/place-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $primitiveAction['code']);
        $this->assertEquals($placeCategory->getEntityIdentifier(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @param IPlaceCategory $placeCategory
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideInvalidDeleteConstructorParameters')]

    public function deleteRequestShouldFailTest(IPlaceCategory $placeCategory)
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        $apiKey = self::getTestApiKey();

        $client = $this->getClientStub();

        $request = new Request($client, $apiKey);
        $placeCategories = new PlaceCategories($request, $this->hydrator);
        $placeCategories->delete([$placeCategory]);
    }

    /**
     * @return array
     */

    public static function provideInvalidDeleteConstructorParameters(): array
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

    public static function provideValidDeleteConstructorParameters(): array
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

    public static function provideUpdateConstructorParameters(): array
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

    public static function provideCreateConstructorParameters(): array
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

    public static function provideGetAllConstructorParameters(): array
    {
        return [
            [
                null,
                false,
                true,
                false,
            ],
            [
                self::getDateTime(),
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