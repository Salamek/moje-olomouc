<?php

declare(strict_types=1);

namespace Salamek\Tests\MojeOlomouc;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

use Salamek\MojeOlomouc\Enum\DateTime;
use Salamek\MojeOlomouc\Enum\ImportantMessageSeverityEnum;
use Salamek\MojeOlomouc\Enum\ImportantMessageTypeEnum;
use Salamek\MojeOlomouc\Enum\RequestActionCodeEnum;
use Salamek\MojeOlomouc\Model\ImportantMessage;
use Salamek\MojeOlomouc\Model\EntityImage;
use Salamek\MojeOlomouc\Model\IImportantMessage;
use Salamek\MojeOlomouc\Operation\ImportantMessages;
use Salamek\MojeOlomouc\Request;
use Salamek\MojeOlomouc\Response;


class ImportantMessagesTest extends BaseTest
{
    private $hydrator;


    public function setUp(): void
    {
        parent::setUp();

        $this->hydrator = $this->getHydrator(\Salamek\MojeOlomouc\Hydrator\IImportantMessage::class);
    }

    #[Test]

    public function getAllShouldBeGoodTest(): void
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

        $places = new ImportantMessages($request, $this->hydrator);
        $response = $places->getAll();

        $this->assertEquals('GET', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/export/important-messages', $catchUri);
        $this->assertInstanceOf(Response::class, $response);
    }


    /**
     * @param IImportantMessage $importantMessage
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideCreateConstructorParameters')]

    public function createShouldBeGoodTest(IImportantMessage $importantMessage)
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
        $importantMessages = new ImportantMessages($request, $this->hydrator);
        $response = $importantMessages->create([$importantMessage]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertIsArray($primitivePayloadItem);
        $this->assertArrayHasKey('importantMessage', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveImportantMessage = $primitivePayloadItem['importantMessage'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertIsArray($primitiveImportantMessage);
        $this->assertIsArray($primitiveAction);
        $this->assertArrayHasKey('text', $primitiveImportantMessage);
        $this->assertArrayHasKey('dateTimeAt', $primitiveImportantMessage);
        $this->assertArrayHasKey('type', $primitiveImportantMessage);
        $this->assertArrayHasKey('severity', $primitiveImportantMessage);
        if (!is_null($importantMessage->getExpireAt())) $this->assertArrayHasKey('expireAt', $primitiveImportantMessage);
        if (!is_null($importantMessage->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveImportantMessage);

        $this->assertEquals($importantMessage->getText(), $primitiveImportantMessage['text']);
        $this->assertEquals($importantMessage->getDateTimeAt()->format(DateTime::NOT_A_ISO8601), $primitiveImportantMessage['dateTimeAt']);
        $this->assertEquals($importantMessage->getType(), $primitiveImportantMessage['type']);
        $this->assertEquals($importantMessage->getSeverity(), $primitiveImportantMessage['severity']);
        if (!is_null($importantMessage->getExpireAt())) $this->assertEquals($importantMessage->getExpireAt()->format(DateTime::NOT_A_ISO8601), $primitiveImportantMessage['expireAt']);
        if (!is_null($importantMessage->getIsVisible())) $this->assertEquals($importantMessage->getIsVisible(), $primitiveImportantMessage['isVisible']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/important-messages', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_CREATE, $primitiveAction['code']);
        $this->assertEquals($importantMessage->getEntityIdentifier(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @param IImportantMessage $importantMessage
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideUpdateConstructorParameters')]

    public function updateShouldBeGoodTest(IImportantMessage $importantMessage)
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
        $importantMessages = new ImportantMessages($request, $this->hydrator);
        $response = $importantMessages->update([$importantMessage]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];

        $this->assertIsArray($primitivePayloadItem);
        $this->assertArrayHasKey('importantMessage', $primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveImportantMessage = $primitivePayloadItem['importantMessage'];
        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertIsArray($primitiveImportantMessage);
        $this->assertIsArray($primitiveAction);
        $this->assertArrayHasKey('text', $primitiveImportantMessage);
        $this->assertArrayHasKey('dateTimeAt', $primitiveImportantMessage);
        $this->assertArrayHasKey('type', $primitiveImportantMessage);
        $this->assertArrayHasKey('severity', $primitiveImportantMessage);
        if (!is_null($importantMessage->getExpireAt())) $this->assertArrayHasKey('expireAt', $primitiveImportantMessage);
        if (!is_null($importantMessage->getIsVisible())) $this->assertArrayHasKey('isVisible', $primitiveImportantMessage);

        $this->assertEquals($importantMessage->getText(), $primitiveImportantMessage['text']);
        $this->assertEquals($importantMessage->getDateTimeAt()->format(DateTime::NOT_A_ISO8601), $primitiveImportantMessage['dateTimeAt']);
        $this->assertEquals($importantMessage->getType(), $primitiveImportantMessage['type']);
        $this->assertEquals($importantMessage->getSeverity(), $primitiveImportantMessage['severity']);
        if (!is_null($importantMessage->getExpireAt())) $this->assertEquals($importantMessage->getExpireAt()->format(DateTime::NOT_A_ISO8601), $primitiveImportantMessage['expireAt']);
        if (!is_null($importantMessage->getIsVisible())) $this->assertEquals($importantMessage->getIsVisible(), $primitiveImportantMessage['isVisible']);
        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/important-messages', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_UPDATE, $primitiveAction['code']);
        $this->assertEquals($importantMessage->getEntityIdentifier(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @param IImportantMessage|null $importantMessage
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideValidDeleteConstructorParameters')]

    public function deleteRequestShouldBeGoodTest(?IImportantMessage $importantMessage = null)
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
        $importantMessages = new ImportantMessages($request, $this->hydrator);
        $response = $importantMessages->delete([$importantMessage]);

        $primitivePayloadItem = $catchRequestInfo['json'][0];
        $this->assertIsArray($primitivePayloadItem);
        $this->assertArrayHasKey('action', $primitivePayloadItem);

        $primitiveAction = $primitivePayloadItem['action'];

        $this->assertIsArray($primitiveAction);

        $this->assertEquals('POST', $catchType);
        $this->assertEquals('Basic '.$apiKey, $catchRequestInfo['headers']['Authorization']);
        $this->assertEquals('/api/import/important-messages', $catchUri);
        $this->assertEquals(RequestActionCodeEnum::ACTION_CODE_DELETE, $primitiveAction['code']);
        $this->assertEquals($importantMessage->getEntityIdentifier(), $primitiveAction['id']);
        $this->assertInstanceOf(Response::class, $response);
    }

    /**
     * @param IImportantMessage $importantMessage
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
#[Test]
#[DataProvider('provideInvalidDeleteConstructorParameters')]

    public function deleteRequestShouldFailTest(IImportantMessage $importantMessage)
    {
        $this->expectException(\Salamek\MojeOlomouc\Exception\InvalidArgumentException::class);
        $apiKey = self::getTestApiKey();

        $client = $this->getClientStub();

        $request = new Request($client, $apiKey);
        $importantMessages = new ImportantMessages($request, $this->hydrator);
        $importantMessages->delete([$importantMessage]);
    }

    /**
     * @return array
     * @throws \Exception
     */

    public static function provideInvalidDeleteConstructorParameters(): array
    {
        return [
            [new ImportantMessage(
                'text-'.mt_rand(),
                self::getDateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING,
                self::getDateTime(),
                false,
                null
            )]
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */

    public static function provideValidDeleteConstructorParameters(): array
    {
        return [
            [new ImportantMessage(
                'text-'.mt_rand(),
                self::getDateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING,
                self::getDateTime(),
                false,
                mt_rand()
            )]
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */

    public static function provideUpdateConstructorParameters(): array
    {
        return [
            [new ImportantMessage(
                'text-'.mt_rand(),
                self::getDateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING,
                self::getDateTime(),
                false,
                mt_rand()
            )],
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */

    public static function provideCreateConstructorParameters(): array
    {
        return [
            [new ImportantMessage(
                'text-'.mt_rand(),
                self::getDateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING
            )],
            [new ImportantMessage(
                'text-'.mt_rand(),
                self::getDateTime(),
                ImportantMessageTypeEnum::TRAFFIC_SITUATION,
                ImportantMessageSeverityEnum::WARNING,
                self::getDateTime(),
                false,
                mt_rand()
            )],
        ];
    }

}