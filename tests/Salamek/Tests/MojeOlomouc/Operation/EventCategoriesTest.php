<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

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
    private $hydrator;


    public function setUp(): void
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(\Salamek\MojeOlomouc\Hydrator\IEventCategory::class);
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
        string $source = EventCategorySourceEnum::PUBLISHED
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

        $eventCategories = new EventCategories($request, $this->hydrator);
        $response = $eventCategories->getAll(
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
        $this->assertEquals('/api/export/event-categories', $catchUri);
        $this->assertInstanceOf(Response::class, $response);
    }


    /**
     * @param IEventCategory $eventCategory
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideCreateConstructorParameters')]

    public function createShouldBeGoodTest(IEventCategory $eventCategory)
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
        $eventCategories = new EventCategories($request, $this->hydrator);
        $response = $eventCategories->create([$eventCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertIsArray($primitivePayloadItem);
        $this->assertArrayHasKey('eventCategory', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveEventCategory = $primitivePayloadItem['eventCategory'];
        $primitiveAction = $primitivePayloadItem['action'];
        
        $this->assertIsArray($primitiveEventCategory);
        $this->assertIsArray($primitiveAction);
        $this->assertArrayHasKey('title', $primitiveEventCategory);
        if (!is_null($eventCategory->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveEventCategory);

        $this->assertEquals($eventCategory->getTitle(), $primitiveEventCategory['title']);
        if (!is_null($eventCategory->getIsVisible())) $this->assertEquals($eventCategory->getIsVisible(), $primitiveEventCategory['isVisible']);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/event-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $primitiveAction['code']);
        $this->assertEquals($eventCategory->getEntityIdentifier(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @param IEventCategory $eventCategory
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideUpdateConstructorParameters')]

    public function updateShouldBeGoodTest(IEventCategory $eventCategory)
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
        $eventCategories = new EventCategories($request, $this->hydrator);
        $response = $eventCategories->update([$eventCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertIsArray($primitivePayloadItem);
        $this->assertArrayHasKey('eventCategory', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveEventCategory = $primitivePayloadItem['eventCategory'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertIsArray($primitiveEventCategory);
        $this->assertIsArray($primitiveAction);
        $this->assertArrayHasKey('title', $primitiveEventCategory);
        if (!is_null($eventCategory->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveEventCategory);

        $this->assertEquals($eventCategory->getTitle(), $primitiveEventCategory['title']);
        if (!is_null($eventCategory->getIsVisible())) $this->assertEquals($eventCategory->getIsVisible(), $primitiveEventCategory['isVisible']);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/event-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $primitiveAction['code']);
        $this->assertEquals($eventCategory->getEntityIdentifier(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @param IEventCategory|null $eventCategory
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideValidDeleteConstructorParameters')]

    public function deleteRequestShouldBeGoodTest(?IEventCategory $eventCategory = null)
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
        $eventCategories = new EventCategories($request, $this->hydrator);
        $response = $eventCategories->delete([$eventCategory]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];
        $this->assertIsArray($primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertIsArray($primitiveAction);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/event-categories', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $primitiveAction['code']);
        $this->assertEquals($eventCategory->getEntityIdentifier(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @param IEventCategory $eventCategory
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideInvalidDeleteConstructorParameters')]

    public function deleteRequestShouldFailTest(IEventCategory $eventCategory)
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        $apiKey = self::getTestApiKey();

        $client = $this->getClientStub();

        $request = new Request($client, $apiKey);
        $eventCategories = new EventCategories($request, $this->hydrator);
        $eventCategories->delete([$eventCategory]);
    }

    /**
     * @return array
     */

    public static function provideInvalidDeleteConstructorParameters(): array
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

    public static function provideValidDeleteConstructorParameters(): array
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

    public static function provideUpdateConstructorParameters(): array
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

    public static function provideCreateConstructorParameters(): array
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
     * @throws \Exception
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